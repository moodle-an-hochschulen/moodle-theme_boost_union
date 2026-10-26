var __defProp = Object.defineProperty;
var __name = (target, value) => __defProp(target, "name", { value, configurable: true });
import { Fragment, jsxDEV } from "react/jsx-dev-runtime";
/**
 * Theme Boost Union - Navigation pill engine for the primary navigation and the smart menu menu bar.
 *
 * This module is a modified copy of core/nav/Nav (lib/js/esm/src/nav/Nav.tsx).
 * The unmodified upstream version is kept in Nav.tsx.upstream next to this file to ease the
 * comparison with future core changes.
 *
 * Modifications compared to the original module:
 * * Add the Boost Union smart menu fields to the NavNode interface (smartmenu, smartmenuitem,
 *   classes, wrapperclasses, tooltip, forceout, helptext, helptextposition, helpicon).
 * * Add the gobacklabel prop which is used by the smart menu carousels.
 * * Render smart menus (and smart menu items with children) as dropdowns with a carousel which
 *   realizes the third level of the smart menu (replacing the legacy theme_boost_union/submenu
 *   AMD module), including the smart menu description and help icon.
 * * Render top-level smart menu items with their formatted HTML label, their classes, their
 *   tooltip and their attributes (NavPill only accepts plain text labels).
 * * Keep smart menus which are configured to be kept outside of the "More" menu out of the
 *   overflow (replacing the legacy theme_boost_union/smartmenu AMD module).
 * * Add the classes, tooltips and attributes of the smart menu items to the dropdown items.
 * * Add the data-key attribute to the top-level list items (as the NonJS fallback markup has it).
 * * Initialize the tooltips and the help icon popovers of the smart menus.
 * * Align the dropdowns of the top-level pills to the right edge of their pill if they would overflow
 *   the viewport otherwise (which can happen in the menu bar which spans the full page width).
 * * Widen the "More" dropdown by its horizontal overflow (if any) whenever its content changes, as browsers
 *   which do not account for its vertical scrollbar would show a horizontal scrollbar otherwise.
 *
 * @module     theme_boost_union/nav/Nav
 * @copyright  2026 Alexander Bias <abias@ssystems.de>
 * @copyright  based on code 2026 Huong Nguyen <huongnv13@gmail.com>
 * @copyright  based on code 2026 Rajneel Totaram <rajneel.totaram@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import {
  Fragment as Fragment2,
  cloneElement,
  isValidElement,
  useEffect,
  useId,
  useLayoutEffect,
  useMemo,
  useRef,
  useState
} from "react";
import { NavPill } from "@moodlehq/design-system";
import { requireAsync } from "@moodle/lms/core/amd";
import { Carousel, Popover, Tooltip } from "bootstrap";
const isNodeActive = /* @__PURE__ */ __name((node) => node.active || node.children.some(isNodeActive), "isNodeActive");
const isSmartMenuNode = /* @__PURE__ */ __name((node) => !!(node.smartmenu || node.smartmenuitem), "isSmartMenuNode");
const joinClasses = /* @__PURE__ */ __name((...classes) => classes.flat().filter(Boolean).join(" "), "joinClasses");
const toBlockDisplayClasses = /* @__PURE__ */ __name((classes = []) => classes.map((cls) => cls.replace(/^(d(?:-(?:sm|md|lg|xl|xxl))?)-inline-flex$/, "$1-block")), "toBlockDisplayClasses");
const toFlexDisplayClasses = /* @__PURE__ */ __name((classes = []) => classes.map((cls) => cls.replace(/^(d(?:-(?:sm|md|lg|xl|xxl))?)-inline-flex$/, "$1-flex")), "toFlexDisplayClasses");
const plainTextLabel = /* @__PURE__ */ __name((html) => {
  const body = new DOMParser().parseFromString(html, "text/html").body;
  const text = body.textContent?.trim() ?? "";
  if (text !== "") {
    return text;
  }
  const icon = body.querySelector("[aria-label], [alt], [title]");
  return (icon?.getAttribute("aria-label") || icon?.getAttribute("alt") || icon?.getAttribute("title") || "").trim();
}, "plainTextLabel");
const withActiveHref = /* @__PURE__ */ __name((nodes, activeHref) => nodes.map((node) => ({
  ...node,
  active: node.href === activeHref,
  children: withActiveHref(node.children, activeHref)
})), "withActiveHref");
const hasNodeWithHref = /* @__PURE__ */ __name((nodes, href) => nodes.some((node) => node.href === href || hasNodeWithHref(node.children, href)), "hasNodeWithHref");
const RESERVED_ATTRIBUTE_NAMES = /* @__PURE__ */ new Set(["id", "class", "disabled"]);
const toAttributeRecord = /* @__PURE__ */ __name((attributes = []) => Object.fromEntries(
  attributes.filter(({ name }) => !RESERVED_ATTRIBUTE_NAMES.has(name)).map(({ name, value }) => [name, String(value)])
), "toAttributeRecord");
const toTooltipRecord = /* @__PURE__ */ __name((node, ontoggle = false) => {
  if (node.tooltip) {
    return ontoggle ? { "data-bu-tooltip": node.tooltip } : { title: node.tooltip, "data-bs-toggle": "tooltip" };
  }
  if (node.title) {
    return { title: node.title };
  }
  return {};
}, "toTooltipRecord");
const TOOLTIP_TRIGGER_SELECTOR = '[data-bs-toggle="tooltip"], [data-bu-tooltip]';
const TOOLTIP_HOST_SELECTOR = ":scope > .mds-nav-pill__label, :scope > .boost-union-smartmenu-toggle-label";
const initTooltip = /* @__PURE__ */ __name((trigger) => {
  const toggletooltip = trigger.dataset.buTooltip;
  if (toggletooltip === void 0) {
    const tooltip2 = Tooltip.getOrCreateInstance(trigger);
    return () => tooltip2.dispose();
  }
  const host = trigger.querySelector(TOOLTIP_HOST_SELECTOR);
  if (!host) {
    return () => void 0;
  }
  host.style.pointerEvents = "none";
  const tooltip = Tooltip.getOrCreateInstance(host, { title: toggletooltip });
  const forward = /* @__PURE__ */ __name((type) => host.dispatchEvent(type.startsWith("mouse") ? new MouseEvent(type) : new Event(type)), "forward");
  const enter = /* @__PURE__ */ __name((type) => () => {
    if (trigger.getAttribute("aria-expanded") !== "true") {
      forward(type);
    }
  }, "enter");
  const leave = /* @__PURE__ */ __name(() => {
    forward("mouseout");
    forward("focusout");
  }, "leave");
  const listeners = [
    ["mouseenter", enter("mouseover")],
    ["mouseleave", () => forward("mouseout")],
    ["focusin", enter("focusin")],
    ["focusout", () => forward("focusout")],
    ["show.bs.dropdown", leave]
  ];
  listeners.forEach(([type, listener]) => trigger.addEventListener(type, listener));
  return () => {
    listeners.forEach(([type, listener]) => trigger.removeEventListener(type, listener));
    tooltip.dispose();
    host.style.pointerEvents = "";
  };
}, "initTooltip");
const resolveGlobalFunction = /* @__PURE__ */ __name((path) => path.split(".").reduce((obj, key) => obj?.[key], window), "resolveGlobalFunction");
const useActionLinkBehavior = /* @__PURE__ */ __name((items) => {
  const actionSignature = useMemo(
    () => JSON.stringify(
      items.filter((item) => item.id && item.actions?.length).map((item) => [item.id, item.actions.map(
        (action) => [action.event, action.jsfunction, action.jsfunctionargs]
      )])
    ),
    [items]
  );
  useEffect(() => {
    const nodesWithActions = items.filter((item) => item.id && item.actions?.length);
    if (nodesWithActions.length === 0) {
      return void 0;
    }
    const cleanups = [];
    nodesWithActions.forEach((item) => {
      const el = document.getElementById(item.id);
      if (!el) {
        return;
      }
      item.actions.forEach((action) => {
        const fn = resolveGlobalFunction(action.jsfunction);
        if (!fn) {
          return;
        }
        const args = action.jsfunctionargs ? JSON.parse(action.jsfunctionargs) : void 0;
        const listener = /* @__PURE__ */ __name((event) => fn(event, args), "listener");
        el.addEventListener(action.event, listener);
        cleanups.push(() => el.removeEventListener(action.event, listener));
      });
    });
    return () => {
      cleanups.forEach((cleanup) => cleanup());
    };
  }, [actionSignature]);
}, "useActionLinkBehavior");
const keepParentMenuOpen = /* @__PURE__ */ __name((event) => event.stopPropagation(), "keepParentMenuOpen");
function DropdownSubmenu({ node, istablist = false }) {
  const id = useId();
  const toggleId = `${id}-toggle`;
  const menuId = `${id}-menu`;
  return (
    // The wrapper only exists to give Bootstrap's dropdown JS a container, so role="none"
    // keeps the toggle a valid child of the enclosing role="menu", as the legacy <li> did.
    /* @__PURE__ */ jsxDEV("div", { className: "dropdown dropdown-submenu", role: "none", onClickCapture: keepParentMenuOpen, children: [
      /* @__PURE__ */ jsxDEV(
        "a",
        {
          id: toggleId,
          className: `dropdown-item dropdown-toggle${isNodeActive(node) ? " active" : ""}`,
          href: "#",
          title: node.title ?? void 0,
          role: "menuitem",
          "data-bs-toggle": "dropdown",
          "data-bs-display": "static",
          "aria-haspopup": "true",
          "aria-expanded": "false",
          "aria-controls": menuId,
          "aria-current": isNodeActive(node) ? "page" : void 0,
          children: node.text
        },
        void 0,
        false,
        {
          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
          lineNumber: 435,
          columnNumber: 13
        },
        this
      ),
      /* @__PURE__ */ jsxDEV("div", { className: "dropdown-menu", id: menuId, role: istablist ? "none" : "menu", "aria-labelledby": toggleId, children: /* @__PURE__ */ jsxDEV(DropdownItems, { items: node.children, istablist }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 454,
        columnNumber: 17
      }, this) }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 453,
        columnNumber: 13
      }, this)
    ] }, void 0, true, {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 434,
      columnNumber: 9
    }, this)
  );
}
__name(DropdownSubmenu, "DropdownSubmenu");
const carouselItemId = /* @__PURE__ */ __name((idprefix, key) => `${idprefix}-carousel-item-${key === null ? "main" : key}`, "carouselItemId");
function SmartMenuHelpIcon({ html }) {
  const wrapperRef = useRef(null);
  useEffect(() => {
    const trigger = wrapperRef.current?.querySelector('[data-bs-toggle="popover"]');
    if (!trigger) {
      return void 0;
    }
    const popover = Popover.getOrCreateInstance(trigger, {
      container: "body",
      trigger: "manual",
      html: true,
      // Like theme_boost/loader, allow tables in the popover content (the menu description may contain them).
      allowList: { ...Popover.Default.allowList, table: [], thead: [], tbody: [], tr: [], th: [], td: [] },
      template: Popover.Default.template.replace('role="tooltip"', 'role="dialog"')
    });
    trigger.setAttribute("aria-haspopup", "dialog");
    return () => {
      popover.dispose();
    };
  }, [html]);
  return /* @__PURE__ */ jsxDEV("div", { ref: wrapperRef, className: "menu-helpicon", dangerouslySetInnerHTML: { __html: html } }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 504,
    columnNumber: 12
  }, this);
}
__name(SmartMenuHelpIcon, "SmartMenuHelpIcon");
function SmartMenuDropdownContent({ node, istablist = false, gobacklabel = "" }) {
  const idprefix = useId();
  const carouselRef = useRef(null);
  const mainId = carouselItemId(idprefix, null);
  const submenus = node.children.filter((child) => !child.divider && child.children.length > 0);
  useEffect(() => {
    const carousel = carouselRef.current;
    if (!carousel) {
      return void 0;
    }
    const dropdown = carousel.closest(".dropdown");
    const goTo = /* @__PURE__ */ __name((targetId) => {
      const target = document.getElementById(targetId);
      if (!target || !target.parentElement) {
        return;
      }
      const index = Array.from(target.parentElement.children).indexOf(target);
      Carousel.getOrCreateInstance(carousel).to(index);
    }, "goTo");
    const handleClick = /* @__PURE__ */ __name((event) => {
      const target = event.target;
      const navigationLink = target?.closest(".carousel-navigation-link");
      if (navigationLink && carousel.contains(navigationLink)) {
        event.preventDefault();
        event.stopPropagation();
        goTo(navigationLink.dataset.carouselTargetId ?? mainId);
        return;
      }
      if (target?.closest(".menu-helpicon")) {
        event.stopPropagation();
      }
    }, "handleClick");
    const handleKeyDown = /* @__PURE__ */ __name((event) => {
      const target = event.target;
      if ((event.key === " " || event.key === "Enter") && target?.matches(".carousel-navigation-link")) {
        event.preventDefault();
        event.stopPropagation();
        goTo(target.dataset.carouselTargetId ?? mainId);
      }
    }, "handleKeyDown");
    const handleHide = /* @__PURE__ */ __name(() => {
      Carousel.getOrCreateInstance(carousel).to(0);
    }, "handleHide");
    const handleSlid = /* @__PURE__ */ __name(() => {
      carousel.querySelector(".carousel-item.active")?.focus();
    }, "handleSlid");
    carousel.addEventListener("click", handleClick, true);
    carousel.addEventListener("keydown", handleKeyDown, true);
    carousel.addEventListener("slid.bs.carousel", handleSlid);
    dropdown?.addEventListener("hide.bs.dropdown", handleHide);
    return () => {
      carousel.removeEventListener("click", handleClick, true);
      carousel.removeEventListener("keydown", handleKeyDown, true);
      carousel.removeEventListener("slid.bs.carousel", handleSlid);
      dropdown?.removeEventListener("hide.bs.dropdown", handleHide);
      Carousel.getInstance(carousel)?.dispose();
    };
  }, [mainId]);
  return /* @__PURE__ */ jsxDEV(
    "div",
    {
      ref: carouselRef,
      className: "carousel slide theme-boost-union-smartmenu-carousel",
      "data-bs-touch": "false",
      "data-bs-interval": "false",
      "data-bs-keyboard": "false",
      children: /* @__PURE__ */ jsxDEV("div", { className: "carousel-inner", children: [
        /* @__PURE__ */ jsxDEV("div", { id: mainId, className: "carousel-item active", role: "menu", tabIndex: -1, children: [
          node.helpicon && /* @__PURE__ */ jsxDEV(SmartMenuHelpIcon, { html: node.helpicon }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 611,
            columnNumber: 39
          }, this),
          node.helptext && node.helptextposition === "above" && /* @__PURE__ */ jsxDEV("div", { className: "menu-description", dangerouslySetInnerHTML: { __html: node.helptext } }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 613,
            columnNumber: 25
          }, this),
          /* @__PURE__ */ jsxDEV(DropdownItems, { items: node.children, istablist, carouselidprefix: idprefix }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 615,
            columnNumber: 21
          }, this),
          node.helptext && node.helptextposition === "below" && /* @__PURE__ */ jsxDEV("div", { className: "menu-description", dangerouslySetInnerHTML: { __html: node.helptext } }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 617,
            columnNumber: 25
          }, this)
        ] }, void 0, true, {
          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
          lineNumber: 610,
          columnNumber: 17
        }, this),
        submenus.map((submenu) => {
          const submenuId = carouselItemId(idprefix, submenu.key);
          const titleId = `${submenuId}-title`;
          return /* @__PURE__ */ jsxDEV(
            "div",
            {
              id: submenuId,
              className: "carousel-item submenu",
              role: "menu",
              tabIndex: -1,
              "aria-label": plainTextLabel(submenu.text),
              children: /* @__PURE__ */ jsxDEV("div", { className: "d-flex flex-column h-100", children: [
                /* @__PURE__ */ jsxDEV("div", { className: "header", children: /* @__PURE__ */ jsxDEV(
                  "a",
                  {
                    href: "#",
                    className: "carousel-navigation-link text-decoration-none text-body",
                    "data-carousel-target-id": mainId,
                    "aria-label": gobacklabel,
                    children: [
                      /* @__PURE__ */ jsxDEV("button", { type: "button", className: "btn btn-icon text-decoration-none text-body", tabIndex: -1, children: [
                        /* @__PURE__ */ jsxDEV("span", { className: "dir-rtl-hide", children: /* @__PURE__ */ jsxDEV("i", { className: "icon fa fa-arrow-left fa-fw", "aria-hidden": "true" }, void 0, false, {
                          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                          lineNumber: 642,
                          columnNumber: 49
                        }, this) }, void 0, false, {
                          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                          lineNumber: 641,
                          columnNumber: 45
                        }, this),
                        /* @__PURE__ */ jsxDEV("span", { className: "dir-ltr-hide", children: /* @__PURE__ */ jsxDEV("i", { className: "icon fa fa-arrow-right fa-fw", "aria-hidden": "true" }, void 0, false, {
                          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                          lineNumber: 645,
                          columnNumber: 49
                        }, this) }, void 0, false, {
                          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                          lineNumber: 644,
                          columnNumber: 45
                        }, this)
                      ] }, void 0, true, {
                        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                        lineNumber: 640,
                        columnNumber: 41
                      }, this),
                      /* @__PURE__ */ jsxDEV("span", { className: "ps-2", id: titleId, children: plainTextLabel(submenu.text) }, void 0, false, {
                        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                        lineNumber: 649,
                        columnNumber: 41
                      }, this)
                    ]
                  },
                  void 0,
                  true,
                  {
                    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                    lineNumber: 634,
                    columnNumber: 37
                  },
                  this
                ) }, void 0, false, {
                  fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                  lineNumber: 633,
                  columnNumber: 33
                }, this),
                /* @__PURE__ */ jsxDEV("div", { className: "dropdown-divider", role: "separator" }, void 0, false, {
                  fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                  lineNumber: 652,
                  columnNumber: 33
                }, this),
                /* @__PURE__ */ jsxDEV("div", { className: "items h-100 overflow-auto", role: "menu", "aria-labelledby": titleId, children: /* @__PURE__ */ jsxDEV(DropdownItems, { items: submenu.children, istablist, itemclasses: "ps-5" }, void 0, false, {
                  fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                  lineNumber: 654,
                  columnNumber: 37
                }, this) }, void 0, false, {
                  fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                  lineNumber: 653,
                  columnNumber: 33
                }, this)
              ] }, void 0, true, {
                fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
                lineNumber: 632,
                columnNumber: 29
              }, this)
            },
            submenu.key,
            false,
            {
              fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
              lineNumber: 624,
              columnNumber: 25
            },
            this
          );
        })
      ] }, void 0, true, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 609,
        columnNumber: 13
      }, this)
    },
    void 0,
    false,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 602,
      columnNumber: 9
    },
    this
  );
}
__name(SmartMenuDropdownContent, "SmartMenuDropdownContent");
function SmartMenuDropdownSubmenu({ node, istablist = false, gobacklabel = "" }) {
  const id = useId();
  const toggleId = `${id}-toggle`;
  const menuId = `${id}-menu`;
  const wrapperRef = useRef(null);
  useEffect(() => {
    const wrapper = wrapperRef.current;
    if (!wrapper) {
      return void 0;
    }
    wrapper.addEventListener("click", keepParentMenuOpen);
    return () => wrapper.removeEventListener("click", keepParentMenuOpen);
  }, []);
  return /* @__PURE__ */ jsxDEV(
    "div",
    {
      ref: wrapperRef,
      className: joinClasses("dropdown dropdown-submenu", toBlockDisplayClasses(node.wrapperclasses)),
      role: "none",
      "data-key": node.key,
      children: [
        /* @__PURE__ */ jsxDEV(
          "a",
          {
            id: toggleId,
            className: joinClasses(
              "dropdown-item dropdown-toggle align-items-center",
              toFlexDisplayClasses(node.classes)
            ),
            href: "#",
            role: "menuitem",
            "data-bs-toggle": "dropdown",
            "data-bs-display": "static",
            "aria-haspopup": "true",
            "aria-expanded": "false",
            "aria-controls": menuId,
            ...toTooltipRecord(node, true),
            children: /* @__PURE__ */ jsxDEV("span", { className: "boost-union-smartmenu-toggle-label", dangerouslySetInnerHTML: { __html: node.text } }, void 0, false, {
              fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
              lineNumber: 722,
              columnNumber: 17
            }, this)
          },
          void 0,
          false,
          {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 706,
            columnNumber: 13
          },
          this
        ),
        /* @__PURE__ */ jsxDEV(
          "div",
          {
            className: "dropdown-menu boost-union-moremenu",
            id: menuId,
            role: istablist ? "none" : "menu",
            "aria-labelledby": toggleId,
            children: /* @__PURE__ */ jsxDEV(SmartMenuDropdownContent, { node, istablist, gobacklabel }, void 0, false, {
              fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
              lineNumber: 730,
              columnNumber: 17
            }, this)
          },
          void 0,
          false,
          {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 724,
            columnNumber: 13
          },
          this
        )
      ]
    },
    void 0,
    true,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 697,
      columnNumber: 9
    },
    this
  );
}
__name(SmartMenuDropdownSubmenu, "SmartMenuDropdownSubmenu");
function DropdownItems({ items, istablist = false, submenus = false, carouselidprefix = null, itemclasses = "", gobacklabel = "" }) {
  useActionLinkBehavior(items);
  return /* @__PURE__ */ jsxDEV(Fragment, { children: items.map((item) => {
    if (item.divider) {
      return /* @__PURE__ */ jsxDEV("div", { className: "dropdown-divider", role: "separator" }, item.key, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 776,
        columnNumber: 28
      }, this);
    }
    if (submenus && item.showchildreninsubmenu && item.children.length > 0) {
      if (isSmartMenuNode(item)) {
        return /* @__PURE__ */ jsxDEV(
          SmartMenuDropdownSubmenu,
          {
            node: item,
            istablist,
            gobacklabel
          },
          item.key,
          false,
          {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 783,
            columnNumber: 29
          },
          this
        );
      }
      return /* @__PURE__ */ jsxDEV(DropdownSubmenu, { node: item, istablist }, item.key, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 791,
        columnNumber: 28
      }, this);
    }
    if (carouselidprefix !== null && item.children.length > 0) {
      return /* @__PURE__ */ jsxDEV(
        "a",
        {
          className: joinClasses("carousel-navigation-link dropdown-item", itemclasses, item.classes),
          href: "#",
          role: "menuitem",
          "data-carousel-target-id": carouselItemId(carouselidprefix, item.key),
          ...toTooltipRecord(item),
          ...toAttributeRecord(item.attributes),
          dangerouslySetInnerHTML: { __html: item.text }
        },
        item.key,
        false,
        {
          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
          lineNumber: 798,
          columnNumber: 25
        },
        this
      );
    }
    let ariaSelected;
    if (istablist) {
      ariaSelected = item.active ? "true" : "false";
    }
    return /* @__PURE__ */ jsxDEV(
      "a",
      {
        id: item.id ?? void 0,
        className: joinClasses("dropdown-item", item.active && "active", itemclasses, item.classes),
        href: item.href ?? "#",
        "aria-current": !istablist && item.active ? "page" : void 0,
        "aria-selected": ariaSelected,
        role: istablist ? "tab" : "menuitem",
        "data-bs-toggle": istablist ? "tab" : void 0,
        "data-text": istablist ? item.text : void 0,
        "data-disableactive": "true",
        ...istablist ? { title: item.title ?? void 0 } : toTooltipRecord(item),
        ...toAttributeRecord(item.attributes),
        dangerouslySetInnerHTML: { __html: item.text }
      },
      item.key,
      false,
      {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 817,
        columnNumber: 21
      },
      this
    );
  }) }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 773,
    columnNumber: 9
  }, this);
}
__name(DropdownItems, "DropdownItems");
function PillDropdownToggle({ label, selected, title, istablist = false, classes = [], attributes = {}, children }) {
  const classNames = ["mds-nav-pill", "dropdown-toggle", selected ? "mds-nav-pill--selected" : null, ...classes].filter(Boolean).join(" ");
  const id = useId();
  const toggleId = `${id}-toggle`;
  const menuId = `${id}-menu`;
  const menu = isValidElement(children) ? cloneElement(children, {
    id: menuId,
    // A role="menu" may only own menuitems, but an istablist dropdown holds role="tab"
    // items (see DropdownItems). role="none" keeps the container out of the accessibility
    // tree so those tabs stay owned by the enclosing role="tablist".
    role: istablist ? "none" : "menu",
    "aria-labelledby": toggleId
  }) : children;
  return /* @__PURE__ */ jsxDEV(Fragment2, { children: [
    /* @__PURE__ */ jsxDEV(
      "a",
      {
        href: "#",
        id: toggleId,
        className: classNames,
        title,
        role: istablist ? "tab" : "menuitem",
        "data-bs-toggle": "dropdown",
        "aria-haspopup": "true",
        "aria-expanded": "false",
        "aria-controls": menuId,
        "aria-current": selected ? "page" : void 0,
        tabIndex: selected ? 0 : -1,
        ...attributes,
        children: [
          selected && /* @__PURE__ */ jsxDEV("span", { className: "mds-nav-pill__indicator", "aria-hidden": "true" }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 933,
            columnNumber: 30
          }, this),
          /* @__PURE__ */ jsxDEV("span", { className: "mds-nav-pill__label", dangerouslySetInnerHTML: { __html: label } }, void 0, false, {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 935,
            columnNumber: 17
          }, this)
        ]
      },
      void 0,
      true,
      {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 916,
        columnNumber: 13
      },
      this
    ),
    menu
  ] }, void 0, true, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 915,
    columnNumber: 9
  }, this);
}
__name(PillDropdownToggle, "PillDropdownToggle");
function TabPill({ node }) {
  const selected = isNodeActive(node);
  return /* @__PURE__ */ jsxDEV(
    "a",
    {
      href: node.href ?? "#",
      className: `mds-nav-pill${selected ? " active" : ""}`,
      title: node.title ?? void 0,
      role: "tab",
      "data-bs-toggle": "tab",
      "data-text": node.text,
      "data-disableactive": "true",
      "aria-selected": selected ? "true" : "false",
      tabIndex: selected ? 0 : -1,
      children: /* @__PURE__ */ jsxDEV("span", { className: "mds-nav-pill__label", dangerouslySetInnerHTML: { __html: node.text } }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 965,
        columnNumber: 13
      }, this)
    },
    void 0,
    false,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 953,
      columnNumber: 9
    },
    this
  );
}
__name(TabPill, "TabPill");
function SmartMenuPill({ node }) {
  const selected = isNodeActive(node);
  return /* @__PURE__ */ jsxDEV(
    "a",
    {
      href: node.href ?? "#",
      className: joinClasses("mds-nav-pill", selected && "mds-nav-pill--selected", node.classes),
      role: "menuitem",
      "aria-current": selected ? "page" : void 0,
      tabIndex: selected ? 0 : -1,
      "data-disableactive": "true",
      ...toTooltipRecord(node),
      ...toAttributeRecord(node.attributes),
      children: [
        selected && /* @__PURE__ */ jsxDEV("span", { className: "mds-nav-pill__indicator", "aria-hidden": "true" }, void 0, false, {
          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
          lineNumber: 995,
          columnNumber: 26
        }, this),
        /* @__PURE__ */ jsxDEV("span", { className: "mds-nav-pill__label", dangerouslySetInnerHTML: { __html: node.text } }, void 0, false, {
          fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
          lineNumber: 996,
          columnNumber: 13
        }, this)
      ]
    },
    void 0,
    true,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 985,
      columnNumber: 9
    },
    this
  );
}
__name(SmartMenuPill, "SmartMenuPill");
function SubmenuTrigger({ node, istablist = false }) {
  return /* @__PURE__ */ jsxDEV(
    PillDropdownToggle,
    {
      label: node.text,
      selected: isNodeActive(node),
      title: node.title ?? void 0,
      istablist,
      children: /* @__PURE__ */ jsxDEV("div", { className: "dropdown-menu", children: /* @__PURE__ */ jsxDEV(DropdownItems, { items: node.children, istablist }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 1018,
        columnNumber: 17
      }, this) }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 1017,
        columnNumber: 13
      }, this)
    },
    void 0,
    false,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1011,
      columnNumber: 9
    },
    this
  );
}
__name(SubmenuTrigger, "SubmenuTrigger");
function SmartMenuTrigger({ node, istablist = false, gobacklabel = "" }) {
  return /* @__PURE__ */ jsxDEV(
    PillDropdownToggle,
    {
      label: node.text,
      selected: isNodeActive(node),
      istablist,
      classes: node.classes,
      attributes: toTooltipRecord(node, true),
      children: /* @__PURE__ */ jsxDEV("div", { className: "dropdown-menu boost-union-moremenu", children: /* @__PURE__ */ jsxDEV(SmartMenuDropdownContent, { node, istablist, gobacklabel }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 1047,
        columnNumber: 17
      }, this) }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 1046,
        columnNumber: 13
      }, this)
    },
    void 0,
    false,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1039,
      columnNumber: 9
    },
    this
  );
}
__name(SmartMenuTrigger, "SmartMenuTrigger");
const stampMenuItemRole = /* @__PURE__ */ __name((el) => {
  el?.setAttribute("role", "menuitem");
}, "stampMenuItemRole");
const renderPill = /* @__PURE__ */ __name((item, istablist, gobacklabel) => {
  if (item.showchildreninsubmenu && item.children.length > 0) {
    if (isSmartMenuNode(item)) {
      return /* @__PURE__ */ jsxDEV(SmartMenuTrigger, { node: item, istablist, gobacklabel }, void 0, false, {
        fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
        lineNumber: 1085,
        columnNumber: 20
      });
    }
    return /* @__PURE__ */ jsxDEV(SubmenuTrigger, { node: item, istablist }, void 0, false, {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1087,
      columnNumber: 16
    });
  }
  if (isSmartMenuNode(item)) {
    return /* @__PURE__ */ jsxDEV(SmartMenuPill, { node: item }, void 0, false, {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1090,
      columnNumber: 16
    });
  }
  if (istablist) {
    return /* @__PURE__ */ jsxDEV(TabPill, { node: item }, void 0, false, {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1093,
      columnNumber: 16
    });
  }
  const selected = isNodeActive(item);
  return /* @__PURE__ */ jsxDEV(
    NavPill,
    {
      ref: stampMenuItemRole,
      label: item.text,
      href: item.href ?? "#",
      title: item.title ?? void 0,
      selected,
      tabIndex: selected ? 0 : -1,
      "data-disableactive": "true"
    },
    void 0,
    false,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1097,
      columnNumber: 9
    }
  );
}, "renderPill");
const MEASURED_CLASS = "secondarynav-measured";
function Nav({ items, morelabel, istablist, navbarstyle, measuredclass = MEASURED_CLASS, navlabel, gobacklabel = "" }) {
  const menuRef = useRef(null);
  useEffect(() => {
    const menu2 = menuRef.current;
    if (!menu2) {
      return void 0;
    }
    const handleShown = /* @__PURE__ */ __name((event) => {
      const toggle = event.target;
      if (!(toggle instanceof HTMLElement) || !toggle.parentElement?.matches("li.nav-item")) {
        return;
      }
      const dropdown = toggle.parentElement.querySelector(":scope > .dropdown-menu");
      if (!dropdown) {
        return;
      }
      dropdown.classList.remove("dropdown-menu-end");
      const rect = dropdown.getBoundingClientRect();
      if (rect.right > document.documentElement.clientWidth || rect.left < 0) {
        dropdown.classList.add("dropdown-menu-end");
      }
    }, "handleShown");
    const fitMoreMenu = /* @__PURE__ */ __name(() => {
      const dropdown = menu2.querySelector(":scope > li.dropdownmoremenu > .dropdown-menu");
      if (!dropdown) {
        return;
      }
      dropdown.style.width = "";
      if (!dropdown.classList.contains("show")) {
        return;
      }
      const overflow2 = dropdown.scrollWidth - dropdown.clientWidth;
      if (overflow2 > 0) {
        dropdown.style.width = `${dropdown.offsetWidth + overflow2}px`;
      }
    }, "fitMoreMenu");
    menu2.addEventListener("shown.bs.dropdown", handleShown);
    menu2.addEventListener("shown.bs.dropdown", fitMoreMenu);
    menu2.addEventListener("hidden.bs.dropdown", fitMoreMenu);
    menu2.addEventListener("slid.bs.carousel", fitMoreMenu);
    window.addEventListener("resize", fitMoreMenu);
    return () => {
      menu2.removeEventListener("shown.bs.dropdown", handleShown);
      menu2.removeEventListener("shown.bs.dropdown", fitMoreMenu);
      menu2.removeEventListener("hidden.bs.dropdown", fitMoreMenu);
      menu2.removeEventListener("slid.bs.carousel", fitMoreMenu);
      window.removeEventListener("resize", fitMoreMenu);
    };
  }, []);
  const [activeOverrideHref, setActiveOverrideHref] = useState(() => {
    if (!istablist) {
      return null;
    }
    const hash = window.location.hash;
    return hash && hasNodeWithHref(items, hash) ? hash : null;
  });
  useEffect(() => {
    if (!istablist) {
      return void 0;
    }
    const handleShown = /* @__PURE__ */ __name((event) => {
      const target = event.target;
      if (!(target instanceof HTMLElement) || !menuRef.current?.contains(target)) {
        return;
      }
      const href = target.getAttribute("href");
      if (href && href !== "#") {
        setActiveOverrideHref(href);
      }
    }, "handleShown");
    document.addEventListener("shown.bs.tab", handleShown);
    return () => document.removeEventListener("shown.bs.tab", handleShown);
  }, [istablist]);
  const effectiveItems = istablist && activeOverrideHref ? withActiveHref(items, activeOverrideHref) : items;
  const toplevel = effectiveItems.filter((item) => !item.divider);
  const forced = toplevel.filter((item) => item.forceintomoremenu);
  const rest = toplevel.filter((item) => !item.forceintomoremenu);
  const collapsible = rest.filter((item) => !item.forceout);
  const landmarkRef = useRef(null);
  const [autoOverflowCount, setAutoOverflowCount] = useState(0);
  const [measured, setMeasured] = useState(false);
  const [, forceRemeasure] = useState(0);
  const stepsRef = useRef(0);
  const lastActionRef = useRef(null);
  const shrinkExhaustedRef = useRef(false);
  const itemsKey = items.map((item) => item.key).join(" ");
  const prevItemsKeyRef = useRef(itemsKey);
  useEffect(() => {
    if (!menuRef.current) {
      return void 0;
    }
    let cancelled = false;
    requireAsync("core/menu_navigation").then((menuNavigation) => {
      if (!cancelled && menuRef.current) {
        menuNavigation(menuRef.current);
      }
      return void 0;
    });
    return () => {
      cancelled = true;
    };
  }, []);
  const tooltipsRef = useRef(/* @__PURE__ */ new Map());
  useEffect(() => {
    const tooltips = tooltipsRef.current;
    tooltips.forEach((dispose, trigger) => {
      if (!trigger.isConnected) {
        dispose();
        tooltips.delete(trigger);
      }
    });
    const root = landmarkRef.current ?? menuRef.current;
    root?.querySelectorAll(TOOLTIP_TRIGGER_SELECTOR).forEach((trigger) => {
      if (!tooltips.has(trigger)) {
        tooltips.set(trigger, initTooltip(trigger));
      }
    });
  });
  useEffect(() => {
    const tooltips = tooltipsRef.current;
    return () => {
      tooltips.forEach((dispose) => dispose());
      tooltips.clear();
    };
  }, []);
  useLayoutEffect(() => {
    if (prevItemsKeyRef.current !== itemsKey) {
      prevItemsKeyRef.current = itemsKey;
      stepsRef.current = 0;
      lastActionRef.current = null;
      shrinkExhaustedRef.current = false;
      if (autoOverflowCount !== 0) {
        setAutoOverflowCount(0);
        return;
      }
    }
    const menu2 = menuRef.current;
    const container = (landmarkRef.current ?? menu2)?.parentElement;
    if (!menu2 || !container) {
      setMeasured(true);
      return;
    }
    const reveal = /* @__PURE__ */ __name(() => {
      if (!measured) {
        container.classList.add(measuredclass);
      }
    }, "reveal");
    const wrapped = menu2.offsetHeight > container.offsetHeight;
    const bound = 2 * collapsible.length + 2;
    if (wrapped) {
      if (lastActionRef.current === "shrink") {
        lastActionRef.current = null;
        shrinkExhaustedRef.current = true;
        if (stepsRef.current < bound) {
          stepsRef.current += 1;
          setAutoOverflowCount((count) => count + 1);
          return;
        }
      } else if (autoOverflowCount < collapsible.length && stepsRef.current < bound) {
        stepsRef.current += 1;
        lastActionRef.current = "grow";
        setAutoOverflowCount((count) => count + 1);
        return;
      }
      reveal();
      setMeasured(true);
      return;
    }
    if (autoOverflowCount > 0 && !shrinkExhaustedRef.current && stepsRef.current < bound) {
      stepsRef.current += 1;
      lastActionRef.current = "shrink";
      setAutoOverflowCount((count) => Math.max(count - 1, 0));
      return;
    }
    lastActionRef.current = null;
    reveal();
    setMeasured(true);
  });
  useEffect(() => {
    const remeasure = /* @__PURE__ */ __name(() => {
      stepsRef.current = 0;
      lastActionRef.current = null;
      shrinkExhaustedRef.current = false;
      forceRemeasure((tick) => tick + 1);
    }, "remeasure");
    window.addEventListener("resize", remeasure);
    const container = (landmarkRef.current ?? menuRef.current)?.parentElement;
    let observer = null;
    if (container && typeof ResizeObserver !== "undefined") {
      observer = new ResizeObserver(remeasure);
      observer.observe(container);
    }
    return () => {
      window.removeEventListener("resize", remeasure);
      observer?.disconnect();
    };
  }, []);
  const visibleCount = Math.max(collapsible.length - autoOverflowCount, 0);
  const visibleCollapsible = new Set(collapsible.slice(0, visibleCount));
  const visible = rest.filter((item) => item.forceout || visibleCollapsible.has(item));
  const overflow = [...collapsible.slice(visibleCount), ...forced];
  const itemRole = "none";
  useEffect(() => {
    if (!istablist) {
      return void 0;
    }
    const keys = ["ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown", "Home", "End", " "];
    const handleKeyDown = /* @__PURE__ */ __name((event) => {
      const target = event.target;
      if (!keys.includes(event.key) || !(target instanceof HTMLElement) || target.closest(".dropdown-menu")) {
        return;
      }
      if (target.matches('[data-bs-toggle="dropdown"]') && !["ArrowLeft", "ArrowRight", "Home", "End"].includes(event.key)) {
        return;
      }
      const stops = Array.from(menuRef.current?.querySelectorAll(':scope > li > a[role="tab"]') ?? []).filter((stop) => !stop.closest(".d-none"));
      const index = stops.indexOf(target);
      if (index === -1) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      if (event.key === " ") {
        target.click();
        return;
      }
      let next;
      if (event.key === "Home") {
        next = stops[0];
      } else if (event.key === "End") {
        next = stops[stops.length - 1];
      } else {
        const step = event.key === "ArrowRight" || event.key === "ArrowDown" ? 1 : -1;
        next = stops[(index + step + stops.length) % stops.length];
      }
      next.focus();
    }, "handleKeyDown");
    window.addEventListener("keydown", handleKeyDown, true);
    return () => window.removeEventListener("keydown", handleKeyDown, true);
  }, [istablist]);
  const moreItem = /* @__PURE__ */ jsxDEV(PillDropdownToggle, { label: morelabel, selected: overflow.some(isNodeActive), istablist, children: /* @__PURE__ */ jsxDEV("div", { className: "dropdown-menu dropdown-menu-start", "data-region": "moredropdown", children: /* @__PURE__ */ jsxDEV(DropdownItems, { items: overflow, istablist, submenus: true, gobacklabel }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 1510,
    columnNumber: 17
  }, this) }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 1509,
    columnNumber: 13
  }, this) }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 1508,
    columnNumber: 9
  }, this);
  const menu = /* @__PURE__ */ jsxDEV(
    "ul",
    {
      ref: menuRef,
      className: ["nav", "more-nav", navbarstyle].filter(Boolean).join(" "),
      role: istablist ? "tablist" : "menubar",
      children: [
        visible.map((item) => {
          const isSubmenuTrigger = item.showchildreninsubmenu && item.children.length > 0;
          return /* @__PURE__ */ jsxDEV(
            "li",
            {
              role: itemRole,
              className: joinClasses(
                "nav-item d-flex align-items-center",
                isSubmenuTrigger && "dropdown",
                item.wrapperclasses
              ),
              "data-key": item.key,
              children: renderPill(item, istablist, gobacklabel)
            },
            item.key,
            false,
            {
              fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
              lineNumber: 1524,
              columnNumber: 21
            },
            this
          );
        }),
        /* @__PURE__ */ jsxDEV(
          "li",
          {
            role: itemRole,
            className: `nav-item d-flex align-items-center dropdown dropdownmoremenu${overflow.length === 0 ? " d-none" : ""}`,
            children: moreItem
          },
          void 0,
          false,
          {
            fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
            lineNumber: 1538,
            columnNumber: 13
          },
          this
        )
      ]
    },
    void 0,
    true,
    {
      fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
      lineNumber: 1516,
      columnNumber: 9
    },
    this
  );
  if (!navlabel) {
    return menu;
  }
  return /* @__PURE__ */ jsxDEV("nav", { ref: landmarkRef, "aria-label": navlabel, children: menu }, void 0, false, {
    fileName: "public/theme/boost_union/js/esm/src/nav/Nav.tsx",
    lineNumber: 1560,
    columnNumber: 9
  }, this);
}
__name(Nav, "Nav");
export {
  Nav as default
};
//# sourceMappingURL=Nav.dev.js.map
