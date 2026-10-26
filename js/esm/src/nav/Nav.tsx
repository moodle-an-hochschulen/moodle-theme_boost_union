// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

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

import {Fragment, cloneElement, isValidElement, useEffect, useId, useLayoutEffect, useMemo, useRef, useState,
    type ReactElement, type ReactNode} from 'react';
import {NavPill} from '@moodlehq/design-system';
import {requireAsync} from '@moodle/lms/core/amd';
import {Carousel, Popover, Tooltip} from 'bootstrap';

export interface NavActionLinkAction {
    id: string;
    event: string;
    jsfunction: string;
    jsfunctionargs?: string | false;
}

export interface NavNode {
    key: string;
    text: string;
    href: string | null;
    active: boolean;
    forceintomoremenu: boolean;
    showchildreninsubmenu: boolean;
    children: NavNode[];
    id?: string | null;
    title?: string | null;
    attributes?: {name: string; value: unknown}[];
    actions?: NavActionLinkAction[];
    /**
     * A separator rather than a real item. Only ever set on children, and rendered as a
     * Bootstrap dropdown-divider, matching legacy moremenu_children.mustache.
     */
    divider?: boolean;
    // Boost Union modification: The smart menu fields.
    /** Whether this node is a Boost Union smart menu (a menu in submenu mode). */
    smartmenu?: boolean;
    /** Whether this node is a Boost Union smart menu item. */
    smartmenuitem?: boolean;
    /** Classes for the link (a) element of the node. */
    classes?: string[];
    /** Classes for the list item (li) element of the node. */
    wrapperclasses?: string[];
    /** The tooltip of the node. */
    tooltip?: string | null;
    /** Whether this node must be kept outside of the "More" menu. */
    forceout?: boolean;
    /** The (HTML) description of a smart menu. */
    helptext?: string | null;
    /** Where the description of a smart menu is shown. */
    helptextposition?: 'above' | 'below' | null;
    /** The rendered help icon (HTML) of a smart menu. */
    helpicon?: string | null;
}

export interface NavProps {
    items: NavNode[];
    morelabel: string;
    istablist: boolean;
    /** Extra class for the <ul>, e.g. "navbar-nav" for the primary navigation. */
    navbarstyle?: string;
    /** Class added to the mount point once the overflow split has settled. */
    measuredclass?: string;
    /**
     * Accessible name for the navigation landmark wrapping the menu, e.g. "Course menu".
     *
     * When set, the menu is wrapped in a named <nav>. When omitted, a bare <ul> is rendered and
     * the caller is responsible for whatever landmark the menu belongs to: the primary navigation
     * already sits inside the navbar's own landmark and must not nest a second one inside it.
     */
    navlabel?: string;
    // Boost Union modification: The smart menu props.
    /** The label of the "Go back" links in the smart menu carousels. */
    gobacklabel?: string;
}

/**
 * Check whether a node, or any of its descendants, is the currently active node.
 *
 * Used to highlight a submenu's trigger pill when the active page is one of its children.
 *
 * @param node The node to check.
 * @returns True if the node or one of its descendants is active.
 */
const isNodeActive = (node: NavNode): boolean =>
    node.active || node.children.some(isNodeActive);

/**
 * Boost Union modification: Check whether a node is a Boost Union smart menu node
 * (either a smart menu or a smart menu item).
 *
 * @param node The node to check.
 * @returns True if the node is a smart menu node.
 */
const isSmartMenuNode = (node: NavNode): boolean => !!(node.smartmenu || node.smartmenuitem);

/**
 * Boost Union modification: Join the given classes to a class string.
 *
 * @param classes The classes to join.
 * @returns The joined class string.
 */
const joinClasses = (...classes: (string | string[] | null | undefined | false)[]): string =>
    classes.flat().filter(Boolean).join(' ');

/**
 * Boost Union modification: Convert the responsive display classes of a node (d-inline-flex, d-md-inline-flex, ...)
 * to their block counterparts.
 *
 * The smart menu items carry inline-flex display classes (which double as device visibility classes) for their
 * navigation pills. A block container like the nested smart menu in the "More" menu would lay out its toggle and its
 * dropdown side by side with them.
 *
 * @param classes The classes of the node.
 * @returns The classes with the display classes converted.
 */
const toBlockDisplayClasses = (classes: string[] = []): string[] =>
    classes.map((cls) => cls.replace(/^(d(?:-(?:sm|md|lg|xl|xxl))?)-inline-flex$/, '$1-block'));

/**
 * Boost Union modification: Convert the responsive display classes of a node (d-inline-flex, d-md-inline-flex, ...)
 * to their flex counterparts.
 *
 * The toggle of a smart menu item which is nested in the "More" menu must be a (block level) flex container which
 * centers its label and the dropdown caret vertically, just like Moodle core styles the active items in the "More"
 * menu. As an inline flex container, the caret would be aligned to the top of the toggle.
 *
 * @param classes The classes of the node.
 * @returns The classes with the display classes converted.
 */
const toFlexDisplayClasses = (classes: string[] = []): string[] =>
    classes.map((cls) => cls.replace(/^(d(?:-(?:sm|md|lg|xl|xxl))?)-inline-flex$/, '$1-flex'));

/**
 * Boost Union modification: Get the plain text of the given formatted label.
 *
 * The HTML tags are stripped from the label. If the label does not contain any text at all (an item which
 * is configured to show its icon only), the accessible name of the icon is used instead.
 *
 * @param html The formatted label.
 * @returns The plain text label.
 */
const plainTextLabel = (html: string): string => {
    const body = new DOMParser().parseFromString(html, 'text/html').body;
    const text = body.textContent?.trim() ?? '';
    if (text !== '') {
        return text;
    }
    const icon = body.querySelector('[aria-label], [alt], [title]');
    return (icon?.getAttribute('aria-label') || icon?.getAttribute('alt') || icon?.getAttribute('title') || '').trim();
};

/**
 * Returns a copy of the given nodes with `active` recomputed against a specific href, recursively.
 *
 * The server computes `active` once, from the page's URL, which can't reflect a tab Bootstrap
 * later activates client-side. Without this, the pane Bootstrap controls and the pill React highlights can disagree.
 *
 * @param nodes The nodes to recompute.
 * @param activeHref The href of the tab Bootstrap reports as active.
 * @returns A new node array with `active` set to `node.href === activeHref` throughout.
 */
const withActiveHref = (nodes: NavNode[], activeHref: string): NavNode[] => nodes.map((node) => ({
    ...node,
    active: node.href === activeHref,
    children: withActiveHref(node.children, activeHref),
}));

/**
 * Checks whether any node, or one of its descendants, has the given href.
 *
 * @param nodes The nodes to search.
 * @param href The href to look for.
 * @returns True if some node in the tree has that href.
 */
const hasNodeWithHref = (nodes: NavNode[], href: string): boolean =>
    nodes.some((node) => node.href === href || hasNodeWithHref(node.children, href));

/**
 * Attribute names excluded from toAttributeRecord()'s output because they're already handled
 * explicitly by DropdownItems (id, via item.id).
 */
const RESERVED_ATTRIBUTE_NAMES = new Set(['id', 'class', 'disabled']);

/**
 * Convert a node's exported action_link attributes into a plain DOM attribute record.
 *
 * @param attributes The {name, value} pairs from more_menu.php's actionattributes() export.
 * @returns A record suitable for spreading onto a JSX element.
 */
const toAttributeRecord = (attributes: {name: string; value: unknown}[] = []): Record<string, string> =>
    Object.fromEntries(
        attributes
            .filter(({name}) => !RESERVED_ATTRIBUTE_NAMES.has(name))
            .map(({name, value}) => [name, String(value)]),
    );

/**
 * Boost Union modification: Convert a node's tooltip into the attributes of a Bootstrap tooltip.
 *
 * Dropdown toggles are owned by their Bootstrap Dropdown and Bootstrap allows only one component instance per element,
 * so a Tooltip instance can not be attached to a toggle itself (the Dropdown instance would not be stored anymore and
 * the dropdown could not be closed by Bootstrap). Their tooltip text is carried in the data-bu-tooltip attribute
 * instead and the tooltip is attached to the label element within the toggle (see initTooltip). Both kinds of
 * triggers are picked up by the tooltip initialization in Nav (see TOOLTIP_TRIGGER_SELECTOR).
 *
 * @param node The node.
 * @param ontoggle Whether the attributes are meant for a dropdown toggle.
 * @returns A record suitable for spreading onto a JSX element.
 */
const toTooltipRecord = (node: NavNode, ontoggle = false): Record<string, string> => {
    if (node.tooltip) {
        return ontoggle
            ? {'data-bu-tooltip': node.tooltip}
            : {title: node.tooltip, 'data-bs-toggle': 'tooltip'};
    }
    if (node.title) {
        return {title: node.title};
    }
    return {};
};

/**
 * Boost Union modification: The elements which get a Bootstrap tooltip (see toTooltipRecord).
 */
const TOOLTIP_TRIGGER_SELECTOR = '[data-bs-toggle="tooltip"], [data-bu-tooltip]';

/**
 * Boost Union modification: The label element within a dropdown toggle which hosts the tooltip of the toggle
 * (see toTooltipRecord). The top-level pills carry NavPill's label span, the toggles of the smart menus which are
 * nested within the "More" menu wrap their label in a span of their own (see SmartMenuDropdownSubmenu).
 */
const TOOLTIP_HOST_SELECTOR = ':scope > .mds-nav-pill__label, :scope > .boost-union-smartmenu-toggle-label';

/**
 * Boost Union modification: A function which disposes an initialized tooltip again (see initTooltip).
 */
type TooltipDisposer = () => void;

/**
 * Boost Union modification: Initialize the Bootstrap tooltip of a tooltip trigger (see toTooltipRecord).
 *
 * The tooltip of a dropdown toggle is attached to the label element within the toggle. The mouse and focus events
 * of the toggle are forwarded to the label element so that Bootstrap's own 'hover focus' trigger handling runs for
 * the whole toggle (Bootstrap listens to mouseover and mouseout rather than to mouseenter and mouseleave for its hover
 * trigger). Bootstrap's manual show() and hide() calls are deliberately not used as they do not keep Bootstrap's
 * hover state in sync when they are called while a show is still pending or a show transition is still running.
 * The label element itself does not receive any pointer events, otherwise its own mouse events would hide the tooltip
 * as soon as the mouse moves from the label to the padding of the toggle.
 * The tooltip is hidden as soon as the dropdown of the toggle opens (it would overlap the dropdown otherwise) and it is
 * not shown again while the dropdown is open.
 *
 * @param trigger The tooltip trigger.
 * @returns A function which disposes the tooltip again.
 */
const initTooltip = (trigger: HTMLElement): TooltipDisposer => {
    const toggletooltip = trigger.dataset.buTooltip;
    if (toggletooltip === undefined) {
        const tooltip = Tooltip.getOrCreateInstance(trigger);
        return () => tooltip.dispose();
    }

    const host = trigger.querySelector<HTMLElement>(TOOLTIP_HOST_SELECTOR);
    if (!host) {
        return () => undefined;
    }
    host.style.pointerEvents = 'none';
    const tooltip = Tooltip.getOrCreateInstance(host, {title: toggletooltip});
    const forward = (type: string) => host.dispatchEvent(type.startsWith('mouse') ? new MouseEvent(type) : new Event(type));
    const enter = (type: string) => () => {
        if (trigger.getAttribute('aria-expanded') !== 'true') {
            forward(type);
        }
    };
    const leave = () => {
        forward('mouseout');
        forward('focusout');
    };
    const listeners: [string, () => void][] = [
        ['mouseenter', enter('mouseover')],
        ['mouseleave', () => forward('mouseout')],
        ['focusin', enter('focusin')],
        ['focusout', () => forward('focusout')],
        ['show.bs.dropdown', leave],
    ];
    listeners.forEach(([type, listener]) => trigger.addEventListener(type, listener));
    return () => {
        listeners.forEach(([type, listener]) => trigger.removeEventListener(type, listener));
        tooltip.dispose();
        host.style.pointerEvents = '';
    };
};

/**
 * Resolve a dotted global function reference (e.g. "openpopup", "M.util.someFunction") to the
 * actual function, mirroring how core/actions.mustache inserts {{{jsfunction}}} as raw JS.
 *
 * @param path The dotted path to resolve from `window`.
 * @returns The resolved function, or undefined if any segment of the path is missing.
 */
const resolveGlobalFunction = (path: string): ((...args: unknown[]) => void) | undefined =>
    path.split('.').reduce<any>((obj, key) => obj?.[key], window as unknown as Record<string, unknown>);

/**
 * Re-registers each item's action_link actions (e.g. popup_action) as plain DOM event listeners,
 * standing in for the inline binding core/actions.mustache's {{#js}} block used to do server-side.
 * Needed because the React export only carries href/attributes, not arbitrary inline JS: nodes
 * like "Print book"/"Print chapter" rely on a popup_action to open a new window, and lose that
 * behaviour without this.
 *
 * @param items The nodes to scan for actions.
 */
const useActionLinkBehavior = (items: NavNode[]): void => {
    // Callers like `overflow` rebuild `items` as a new array on every render, even when the
    // underlying actions haven't changed. Keying the effect below off this content signature,
    // rather than off `items` itself, avoids tearing down and rebinding every listener on
    // each such re-render.
    const actionSignature = useMemo(
        () => JSON.stringify(
            items
                .filter((item) => item.id && item.actions?.length)
                .map((item) => [item.id, item.actions!.map(
                    (action) => [action.event, action.jsfunction, action.jsfunctionargs],
                )]),
        ),
        [items],
    );

    useEffect(() => {
        const nodesWithActions = items.filter((item) => item.id && item.actions?.length);
        if (nodesWithActions.length === 0) {
            return undefined;
        }

        const cleanups: (() => void)[] = [];

        nodesWithActions.forEach((item) => {
            const el = document.getElementById(item.id!);
            if (!el) {
                return;
            }
            item.actions!.forEach((action) => {
                const fn = resolveGlobalFunction(action.jsfunction);
                if (!fn) {
                    return;
                }
                const args = action.jsfunctionargs ? JSON.parse(action.jsfunctionargs) : undefined;
                const listener = (event: Event) => fn(event, args);
                el.addEventListener(action.event, listener);
                cleanups.push(() => el.removeEventListener(action.event, listener));
            });
        });

        return () => {
            cleanups.forEach((cleanup) => cleanup());
        };
        // ActionSignature captures everything about `items` this effect cares about (see above).
    }, [actionSignature]);
};

/**
 * Keeps the enclosing "More" dropdown open when a nested submenu inside it is clicked, as legacy
 * moremenu.js did with a capturing listener on each nested .dropdown. Bootstrap's own toggle
 * handler is delegated from the document in the capture phase, so it has already opened the
 * submenu by the time this runs; only its bubble-phase clearMenus() is cut off.
 *
 * @param event The click event.
 */
const keepParentMenuOpen = (event: MouseEvent): void => event.stopPropagation();

/**
 * A submenu nested inside the "More" dropdown, for a node whose children belong in a submenu but
 * which has itself been collapsed into the overflow. Legacy moremenu.js moved the whole
 * <li class="dropdown"> into the "More" dropdown rather than flattening it, because such a node
 * has no url of its own: a plain link would be a dead entry with its children unreachable.
 *
 * @param props Component props.
 * @param props.node The collapsed node whose children belong in a submenu.
 * @param props.istablist Whether the navigation is rendered as an ARIA tablist.
 * @returns The rendered submenu toggle and dropdown.
 */
function DropdownSubmenu({node, istablist = false}: {node: NavNode; istablist?: boolean}) {
    const id = useId();
    const toggleId = `${id}-toggle`;
    const menuId = `${id}-menu`;

    return (
        // The wrapper only exists to give Bootstrap's dropdown JS a container, so role="none"
        // keeps the toggle a valid child of the enclosing role="menu", as the legacy <li> did.
        <div className="dropdown dropdown-submenu" role="none" onClickCapture={keepParentMenuOpen}>
            <a
                id={toggleId}
                className={`dropdown-item dropdown-toggle${isNodeActive(node) ? ' active' : ''}`}
                href="#"
                title={node.title ?? undefined}
                role="menuitem"
                data-bs-toggle="dropdown"
                // Bootstrap only skips Popper's absolute positioning by itself inside a .navbar,
                // so ask for it explicitly: the submenu expands in place (see moremenu.scss)
                // rather than floating over the "More" menu it belongs to.
                data-bs-display="static"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls={menuId}
                aria-current={isNodeActive(node) ? 'page' : undefined}
            >
                {node.text}
            </a>
            <div className="dropdown-menu" id={menuId} role={istablist ? 'none' : 'menu'} aria-labelledby={toggleId}>
                <DropdownItems items={node.children} istablist={istablist} />
            </div>
        </div>
    );
}

/**
 * Boost Union modification: The ids of the carousel items of a smart menu dropdown.
 *
 * @param idprefix The id prefix of the smart menu dropdown.
 * @param key The key of the node, or null for the main carousel item.
 * @returns The id of the carousel item.
 */
const carouselItemId = (idprefix: string, key: string | null): string =>
    `${idprefix}-carousel-item-${key === null ? 'main' : key}`;

/**
 * Boost Union modification: The smart menu help icon.
 *
 * The help icon is rendered by core/help_icon on the server side. Its popover is initialized here
 * with the same configuration which theme_boost/loader uses for help icons, as theme_boost/loader
 * only initializes the popovers which exist when the page is loaded.
 *
 * @param props Component props.
 * @param props.html The rendered help icon.
 * @returns The rendered help icon wrapper.
 */
function SmartMenuHelpIcon({html}: {html: string}) {
    const wrapperRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const trigger = wrapperRef.current?.querySelector<HTMLElement>('[data-bs-toggle="popover"]');
        if (!trigger) {
            return undefined;
        }
        const popover = Popover.getOrCreateInstance(trigger, {
            container: 'body',
            trigger: 'manual',
            html: true,
            // Like theme_boost/loader, allow tables in the popover content (the menu description may contain them).
            allowList: {...Popover.Default.allowList, table: [], thead: [], tbody: [], tr: [], th: [], td: []},
            template: Popover.Default.template.replace('role="tooltip"', 'role="dialog"'),
        });
        trigger.setAttribute('aria-haspopup', 'dialog');

        return () => {
            popover.dispose();
        };
    }, [html]);

    return <div ref={wrapperRef} className="menu-helpicon" dangerouslySetInnerHTML={{__html: html}} />;
}

/**
 * Boost Union modification: The content of a smart menu dropdown.
 *
 * The content is a Bootstrap carousel: the first carousel item holds the smart menu description,
 * the help icon and the smart menu items, every further carousel item holds the children of a
 * smart menu item (the third level of the smart menu). This replaces the legacy
 * theme_boost_union/submenu AMD module.
 *
 * @param props Component props.
 * @param props.node The smart menu node.
 * @param props.istablist Whether the navigation is rendered as an ARIA tablist.
 * @param props.gobacklabel The label of the "Go back" links.
 * @returns The rendered carousel.
 */
function SmartMenuDropdownContent(
    {node, istablist = false, gobacklabel = ''}:
    {node: NavNode; istablist?: boolean; gobacklabel?: string},
) {
    const idprefix = useId();
    const carouselRef = useRef<HTMLDivElement>(null);
    const mainId = carouselItemId(idprefix, null);
    const submenus = node.children.filter((child) => !child.divider && child.children.length > 0);

    useEffect(() => {
        const carousel = carouselRef.current;
        if (!carousel) {
            return undefined;
        }
        const dropdown = carousel.closest('.dropdown');

        // Navigate to the given carousel item.
        const goTo = (targetId: string) => {
            const target = document.getElementById(targetId);
            if (!target || !target.parentElement) {
                return;
            }
            const index = Array.from(target.parentElement.children).indexOf(target);
            Carousel.getOrCreateInstance(carousel).to(index);
        };

        // Handle the clicks on the carousel navigation links and on the help icon.
        // By default, the smart menu dropdown closes on a click event. This behaviour is not desirable as the
        // user needs to be able to navigate through the carousel items (submenus of the smart menu) within the
        // dropdown and to open the help icon popover. Therefore, the propagation of these events is stopped.
        // This is done with a native capturing listener (instead of a React event handler) as the "More" menu
        // stops the propagation of the click events of its nested submenus before React would handle them.
        const handleClick = (event: MouseEvent) => {
            const target = event.target as HTMLElement | null;
            const navigationLink = target?.closest<HTMLElement>('.carousel-navigation-link');
            if (navigationLink && carousel.contains(navigationLink)) {
                event.preventDefault();
                event.stopPropagation();
                goTo(navigationLink.dataset.carouselTargetId ?? mainId);
                return;
            }
            if (target?.closest('.menu-helpicon')) {
                event.stopPropagation();
            }
        };

        // Handle the keyboard activation of the carousel navigation links.
        const handleKeyDown = (event: KeyboardEvent) => {
            const target = event.target as HTMLElement | null;
            if ((event.key === ' ' || event.key === 'Enter') && target?.matches('.carousel-navigation-link')) {
                event.preventDefault();
                event.stopPropagation();
                goTo(target.dataset.carouselTargetId ?? mainId);
            }
        };

        // Reset the carousel to the first (main) carousel item once the dropdown is closed.
        const handleHide = () => {
            Carousel.getOrCreateInstance(carousel).to(0);
        };

        // Set the focus on the newly activated carousel item.
        const handleSlid = () => {
            carousel.querySelector<HTMLElement>('.carousel-item.active')?.focus();
        };

        carousel.addEventListener('click', handleClick, true);
        carousel.addEventListener('keydown', handleKeyDown, true);
        carousel.addEventListener('slid.bs.carousel', handleSlid);
        dropdown?.addEventListener('hide.bs.dropdown', handleHide);

        return () => {
            carousel.removeEventListener('click', handleClick, true);
            carousel.removeEventListener('keydown', handleKeyDown, true);
            carousel.removeEventListener('slid.bs.carousel', handleSlid);
            dropdown?.removeEventListener('hide.bs.dropdown', handleHide);
            Carousel.getInstance(carousel)?.dispose();
        };
    }, [mainId]);

    return (
        <div
            ref={carouselRef}
            className="carousel slide theme-boost-union-smartmenu-carousel"
            data-bs-touch="false"
            data-bs-interval="false"
            data-bs-keyboard="false"
        >
            <div className="carousel-inner">
                <div id={mainId} className="carousel-item active" role="menu" tabIndex={-1}>
                    {node.helpicon && <SmartMenuHelpIcon html={node.helpicon} />}
                    {node.helptext && node.helptextposition === 'above' && (
                        <div className="menu-description" dangerouslySetInnerHTML={{__html: node.helptext}} />
                    )}
                    <DropdownItems items={node.children} istablist={istablist} carouselidprefix={idprefix} />
                    {node.helptext && node.helptextposition === 'below' && (
                        <div className="menu-description" dangerouslySetInnerHTML={{__html: node.helptext}} />
                    )}
                </div>
                {submenus.map((submenu) => {
                    const submenuId = carouselItemId(idprefix, submenu.key);
                    const titleId = `${submenuId}-title`;
                    return (
                        <div
                            key={submenu.key}
                            id={submenuId}
                            className="carousel-item submenu"
                            role="menu"
                            tabIndex={-1}
                            aria-label={plainTextLabel(submenu.text)}
                        >
                            <div className="d-flex flex-column h-100">
                                <div className="header">
                                    <a
                                        href="#"
                                        className="carousel-navigation-link text-decoration-none text-body"
                                        data-carousel-target-id={mainId}
                                        aria-label={gobacklabel}
                                    >
                                        <button type="button" className="btn btn-icon text-decoration-none text-body" tabIndex={-1}>
                                            <span className="dir-rtl-hide">
                                                <i className="icon fa fa-arrow-left fa-fw" aria-hidden="true" />
                                            </span>
                                            <span className="dir-ltr-hide">
                                                <i className="icon fa fa-arrow-right fa-fw" aria-hidden="true" />
                                            </span>
                                        </button>
                                        {/* The title is shown as plain text (like the submenu titles in the user menu). */}
                                        <span className="ps-2" id={titleId}>{plainTextLabel(submenu.text)}</span>
                                    </a>
                                </div>
                                <div className="dropdown-divider" role="separator" />
                                <div className="items h-100 overflow-auto" role="menu" aria-labelledby={titleId}>
                                    <DropdownItems items={submenu.children} istablist={istablist} itemclasses="ps-5" />
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>
        </div>
    );
}

/**
 * Boost Union modification: A smart menu nested inside the "More" dropdown, for a smart menu which
 * has been collapsed into the overflow. This is the smart menu counterpart of DropdownSubmenu.
 *
 * @param props Component props.
 * @param props.node The collapsed smart menu node.
 * @param props.istablist Whether the navigation is rendered as an ARIA tablist.
 * @param props.gobacklabel The label of the "Go back" links in the carousel.
 * @returns The rendered submenu toggle and dropdown.
 */
function SmartMenuDropdownSubmenu(
    {node, istablist = false, gobacklabel = ''}:
    {node: NavNode; istablist?: boolean; gobacklabel?: string},
) {
    const id = useId();
    const toggleId = `${id}-toggle`;
    const menuId = `${id}-menu`;
    const wrapperRef = useRef<HTMLDivElement>(null);

    // Keep the "More" dropdown open when the nested smart menu is clicked (see keepParentMenuOpen).
    // Unlike DropdownSubmenu, this is done with a native listener in the bubbling phase so that the
    // capturing listeners of the carousel inside the smart menu are still run.
    useEffect(() => {
        const wrapper = wrapperRef.current;
        if (!wrapper) {
            return undefined;
        }
        wrapper.addEventListener('click', keepParentMenuOpen);
        return () => wrapper.removeEventListener('click', keepParentMenuOpen);
    }, []);

    return (
        <div
            ref={wrapperRef}
            className={joinClasses('dropdown dropdown-submenu', toBlockDisplayClasses(node.wrapperclasses))}
            role="none"
            data-key={node.key}
        >
            {/* Unlike DropdownSubmenu, the toggle is not marked as active (and thus does not get the tick of the
                active "More" menu items) if an item within the smart menu is active. The tick is shown on the active
                item itself within the smart menu, and the "More" toggle already carries the indicator dot. */}
            <a
                id={toggleId}
                className={joinClasses(
                    'dropdown-item dropdown-toggle align-items-center',
                    toFlexDisplayClasses(node.classes),
                )}
                href="#"
                role="menuitem"
                data-bs-toggle="dropdown"
                data-bs-display="static"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls={menuId}
                {...toTooltipRecord(node, true)}
            >
                {/* The label span hosts the tooltip of the toggle (see TOOLTIP_HOST_SELECTOR). */}
                <span className="boost-union-smartmenu-toggle-label" dangerouslySetInnerHTML={{__html: node.text}} />
            </a>
            <div
                className="dropdown-menu boost-union-moremenu"
                id={menuId}
                role={istablist ? 'none' : 'menu'}
                aria-labelledby={toggleId}
            >
                <SmartMenuDropdownContent node={node} istablist={istablist} gobacklabel={gobacklabel} />
            </div>
        </div>
    );
}

/**
 * Plain dropdown-item links for the "More" overflow menu and submenu dropdowns.
 *
 * @moodlehq/design-system has no menu/dropdown component yet, so this reuses Bootstrap's
 * dropdown-item markup (matching legacy moremenu_children.mustache) rather than NavPill, which
 * is designed for the top-level tab bar only.
 *
 * Boost Union modification: The smart menu items get their classes, tooltips and attributes.
 * Inside a smart menu carousel (carouselidprefix is set), items with children are rendered as
 * carousel navigation links which slide to the carousel item which holds their children.
 *
 * @param props Component props.
 * @param props.items The nodes to render as dropdown items.
 * @param props.istablist Whether these items belong to an istablist nav's top-level overflow
 *                        dropdown (as opposed to a SubmenuTrigger's nested dropdown).
 * @param props.submenus Whether a node whose children belong in a submenu renders as a nested
 *                        submenu rather than a plain link. Only set for the "More" dropdown
 *                        itself: legacy moremenu_children.mustache went no deeper either.
 * @param props.carouselidprefix The id prefix of the enclosing smart menu carousel, if any.
 * @param props.itemclasses Additional classes for all items.
 * @param props.gobacklabel The label of the "Go back" links in the smart menu carousels.
 * @returns The rendered dropdown items.
 */
function DropdownItems(
    {items, istablist = false, submenus = false, carouselidprefix = null, itemclasses = '', gobacklabel = ''}:
    {
        items: NavNode[];
        istablist?: boolean;
        submenus?: boolean;
        carouselidprefix?: string | null;
        itemclasses?: string;
        gobacklabel?: string;
    },
) {
    useActionLinkBehavior(items);

    return (
        <>
            {items.map((item) => {
                if (item.divider) {
                    return <div key={item.key} className="dropdown-divider" role="separator" />;
                }

                if (submenus && item.showchildreninsubmenu && item.children.length > 0) {
                    // Boost Union modification: Smart menus get their own nested submenu.
                    if (isSmartMenuNode(item)) {
                        return (
                            <SmartMenuDropdownSubmenu
                                key={item.key}
                                node={item}
                                istablist={istablist}
                                gobacklabel={gobacklabel}
                            />
                        );
                    }
                    return <DropdownSubmenu key={item.key} node={item} istablist={istablist} />;
                }

                // Boost Union modification: Inside a smart menu carousel, items with children are rendered as
                // carousel navigation links.
                if (carouselidprefix !== null && item.children.length > 0) {
                    return (
                        <a
                            key={item.key}
                            className={joinClasses('carousel-navigation-link dropdown-item', itemclasses, item.classes)}
                            href="#"
                            role="menuitem"
                            data-carousel-target-id={carouselItemId(carouselidprefix, item.key)}
                            {...toTooltipRecord(item)}
                            {...toAttributeRecord(item.attributes)}
                            dangerouslySetInnerHTML={{__html: item.text}}
                        />
                    );
                }

                let ariaSelected: 'true' | 'false' | undefined;
                if (istablist) {
                    ariaSelected = item.active ? 'true' : 'false';
                }

                return (
                    <a
                        key={item.key}
                        id={item.id ?? undefined}
                        className={joinClasses('dropdown-item', item.active && 'active', itemclasses, item.classes)}
                        href={item.href ?? '#'}
                        aria-current={!istablist && item.active ? 'page' : undefined}
                        aria-selected={ariaSelected}
                        // Bootstrap's Tab component only fires "shown.bs.tab" (and sets aria-selected) for elements whose role
                        // is exactly "tab". An overflowed tablist item still needs data-bs-toggle="tab" to switch panes, so it
                        // must also carry role="tab".
                        role={istablist ? 'tab' : 'menuitem'}
                        data-bs-toggle={istablist ? 'tab' : undefined}
                        data-text={istablist ? item.text : undefined}
                        // React owns this item's active state via item.active above regardless of
                        // istablist: without this, core/menu_navigation's legacy click handler
                        // forces aria-current onto whichever dropdown item was clicked even when
                        // the click doesn't actually navigate anywhere (e.g. a stubbed-out href in
                        // tests, or any other no-op link).
                        data-disableactive="true"
                        // Boost Union modification: Tooltips (an istablist item keeps its data-bs-toggle="tab").
                        {...(istablist ? {title: item.title ?? undefined} : toTooltipRecord(item))}
                        {...toAttributeRecord(item.attributes)}
                        // Item.text is exported raw HTML (e.g. an icon plus a visually-hidden "opens in
                        // a new window" span), matching the legacy moremenu_children.mustache's unescaped {{{text}}}.
                        // A plain JSX child would have React auto-escape that markup instead of rendering it.
                        dangerouslySetInnerHTML={{__html: item.text}}
                    />
                );
            })}
        </>
    );
}

/**
 * A dropdown-toggle styled to match NavPill's own markup (indicator dot + label span), for
 * visual consistency with the pills either side of it. @moodlehq/design-system has no
 * menu/dropdown-trigger component, so this reuses NavPill's CSS classes directly on a plain
 * Bootstrap dropdown-toggle anchor.
 *
 * Clones the `id`/`role="menu"`/`aria-labelledby` attributes onto the passed-in dropdown-menu
 * element (matching the legacy moremenu_children.mustache's toggle/menu ARIA wiring), rather
 * than requiring every caller to generate and pass down a matching id itself.
 *
 * Renders the toggle and menu as siblings (no wrapping element of its own): legacy
 * moremenu_children.mustache puts Bootstrap's "dropdown" class directly on the enclosing `<li>`
 * rather than on an extra wrapper `<div>`, so the toggle `<a>` stays a *direct* child of its
 * `<li>`. Several Behat helpers (e.g. behat_navigation::select_on_administration_page()) rely on
 * that `<li>/<a>` structure; callers must add the `dropdown` class to their own `<li>` themselves.
 *
 * Boost Union modification: The toggle gets the given additional classes and attributes.
 *
 * @param props Component props.
 * @param props.label The visible label for the toggle.
 * @param props.selected Whether one of the dropdown's own items is currently active.
 * @param props.title The toggle's tooltip, when the node carries one that differs from its label.
 * @param props.istablist Whether the toggle sits inside an `istablist` nav. When true, the toggle
 *                        gets `role="tab"` to be a valid tablist child; when false it gets
 *                        `role="menuitem"` to be a valid child of the top-level `<ul
 *                        role="menubar">`. (NavPill's plain leaf items get the same treatment from
 *                        stampMenuItemRole below, since @moodlehq/design-system's NavPillProps
 *                        type omits `role` and the component always sets its own.)
 * @param props.classes Additional classes for the toggle.
 * @param props.attributes Additional attributes for the toggle.
 * @param props.children The dropdown menu to render alongside the toggle.
 * @returns The rendered dropdown toggle and menu.
 */
function PillDropdownToggle(
    {label, selected, title, istablist = false, classes = [], attributes = {}, children}:
    {
        label: string;
        selected: boolean;
        title?: string;
        istablist?: boolean;
        classes?: string[];
        attributes?: Record<string, string>;
        children: ReactNode;
    },
) {
    const classNames = ['mds-nav-pill', 'dropdown-toggle', selected ? 'mds-nav-pill--selected' : null, ...classes]
        .filter(Boolean)
        .join(' ');

    const id = useId();
    const toggleId = `${id}-toggle`;
    const menuId = `${id}-menu`;

    const menu = isValidElement(children)
        ? cloneElement(children as ReactElement<{id?: string; role?: string; 'aria-labelledby'?: string}>, {
            id: menuId,
            // A role="menu" may only own menuitems, but an istablist dropdown holds role="tab"
            // items (see DropdownItems). role="none" keeps the container out of the accessibility
            // tree so those tabs stay owned by the enclosing role="tablist".
            role: istablist ? 'none' : 'menu',
            'aria-labelledby': toggleId,
        })
        : children;

    return (
        <Fragment>
            <a
                href="#"
                id={toggleId}
                className={classNames}
                title={title}
                role={istablist ? 'tab' : 'menuitem'}
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls={menuId}
                aria-current={selected ? 'page' : undefined}
                // Roving tabindex, matching legacy moremenu_children.mustache/moremenu.js: the
                // toggle is only a native Tab stop while it owns the active item (a submenu child,
                // or an overflowed top-level item for the "More" toggle).
                tabIndex={selected ? 0 : -1}
                {...attributes}
            >
                {selected && <span className="mds-nav-pill__indicator" aria-hidden="true" />}
                {/* Label may carry raw HTML (see DropdownItems' dangerouslySetInnerHTML above). */}
                <span className="mds-nav-pill__label" dangerouslySetInnerHTML={{__html: label}} />
            </a>
            {menu}
        </Fragment>
    );
}

/**
 * A tab pill rendered for an `istablist` nav (e.g. admin/search.php's site-admin category tabs).
 *
 * @param props Component props.
 * @param props.node The node to render as a tab.
 * @returns The rendered tab.
 */
function TabPill({node}: {node: NavNode}) {
    const selected = isNodeActive(node);

    return (
        <a
            href={node.href ?? '#'}
            className={`mds-nav-pill${selected ? ' active' : ''}`}
            title={node.title ?? undefined}
            role="tab"
            data-bs-toggle="tab"
            data-text={node.text}
            data-disableactive="true"
            aria-selected={selected ? 'true' : 'false'}
            tabIndex={selected ? 0 : -1}
        >
            {/* Node.text may carry raw HTML (see DropdownItems' dangerouslySetInnerHTML above). */}
            <span className="mds-nav-pill__label" dangerouslySetInnerHTML={{__html: node.text}} />
        </a>
    );
}

/**
 * Boost Union modification: A pill for a top-level smart menu item without children.
 *
 * This is the smart menu counterpart of NavPill. It is needed as NavPill only accepts plain text
 * labels while smart menu items carry formatted HTML labels (icons, multi-line labels), and as the
 * smart menu items need their classes, tooltips and attributes.
 *
 * @param props Component props.
 * @param props.node The smart menu item node.
 * @returns The rendered pill.
 */
function SmartMenuPill({node}: {node: NavNode}) {
    const selected = isNodeActive(node);

    return (
        <a
            href={node.href ?? '#'}
            className={joinClasses('mds-nav-pill', selected && 'mds-nav-pill--selected', node.classes)}
            role="menuitem"
            aria-current={selected ? 'page' : undefined}
            tabIndex={selected ? 0 : -1}
            data-disableactive="true"
            {...toTooltipRecord(node)}
            {...toAttributeRecord(node.attributes)}
        >
            {selected && <span className="mds-nav-pill__indicator" aria-hidden="true" />}
            <span className="mds-nav-pill__label" dangerouslySetInnerHTML={{__html: node.text}} />
        </a>
    );
}

/**
 * A dropdown trigger for a node whose children should render in a submenu.
 *
 * @param props Component props.
 * @param props.node The node whose children should render in a submenu.
 * @param props.istablist Whether the nav is rendered as an ARIA tablist.
 * @returns The rendered submenu trigger and dropdown.
 */
function SubmenuTrigger({node, istablist = false}: {node: NavNode; istablist?: boolean}) {
    return (
        <PillDropdownToggle
            label={node.text}
            selected={isNodeActive(node)}
            title={node.title ?? undefined}
            istablist={istablist}
        >
            <div className="dropdown-menu">
                <DropdownItems items={node.children} istablist={istablist} />
            </div>
        </PillDropdownToggle>
    );
}

/**
 * Boost Union modification: A dropdown trigger for a smart menu (or a smart menu item with children).
 * This is the smart menu counterpart of SubmenuTrigger.
 *
 * @param props Component props.
 * @param props.node The smart menu node.
 * @param props.istablist Whether the nav is rendered as an ARIA tablist.
 * @param props.gobacklabel The label of the "Go back" links in the carousel.
 * @returns The rendered submenu trigger and dropdown.
 */
function SmartMenuTrigger(
    {node, istablist = false, gobacklabel = ''}:
    {node: NavNode; istablist?: boolean; gobacklabel?: string},
) {
    return (
        <PillDropdownToggle
            label={node.text}
            selected={isNodeActive(node)}
            istablist={istablist}
            classes={node.classes}
            attributes={toTooltipRecord(node, true)}
        >
            <div className="dropdown-menu boost-union-moremenu">
                <SmartMenuDropdownContent node={node} istablist={istablist} gobacklabel={gobacklabel} />
            </div>
        </PillDropdownToggle>
    );
}

/**
 * Ref callback stamping role="menuitem" onto a NavPill's anchor.
 *
 * A NavPill cannot be given a role: @moodlehq/design-system's NavPillProps omits `role`, and the
 * component overwrites whatever is spread in with its own value, which is undefined unless the
 * pill is disabled. Its <li> is role="none", so the roleless anchor is exposed as an owned child
 * of the enclosing <ul role="menubar"> — a critical axe aria-required-children violation
 * ("Element has children which are not allowed"), and leaves the pills announced as plain links
 * rather than as the menu items core/menu_navigation drives with the arrow keys.
 *
 * So set it on the DOM node instead, until NavPill accepts a role of its own. React never undoes
 * this: `role` is undefined in both the previous and the next props, so it is never diffed.
 *
 * @param el The pill's anchor, or null once it is unmounted.
 */
const stampMenuItemRole = (el: HTMLAnchorElement | null): void => {
    el?.setAttribute('role', 'menuitem');
};

/**
 * Render the appropriate pill for a visible top-level node.
 *
 * Boost Union modification: Smart menu nodes are rendered with their own components.
 *
 * @param item The node to render.
 * @param istablist Whether the nav is rendered as an ARIA tablist.
 * @param gobacklabel The label of the "Go back" links in the smart menu carousels.
 * @returns The rendered pill.
 */
const renderPill = (item: NavNode, istablist: boolean, gobacklabel: string) => {
    if (item.showchildreninsubmenu && item.children.length > 0) {
        if (isSmartMenuNode(item)) {
            return <SmartMenuTrigger node={item} istablist={istablist} gobacklabel={gobacklabel} />;
        }
        return <SubmenuTrigger node={item} istablist={istablist} />;
    }
    if (isSmartMenuNode(item)) {
        return <SmartMenuPill node={item} />;
    }
    if (istablist) {
        return <TabPill node={item} />;
    }
    const selected = isNodeActive(item);
    return (
        <NavPill
            ref={stampMenuItemRole}
            label={item.text}
            href={item.href ?? '#'}
            title={item.title ?? undefined}
            selected={selected}
            // Roving tabindex, matching legacy moremenu_children.mustache's
            // {{^isactive}}tabindex="-1"{{/isactive}}: exactly one top-level item is ever a native
            // Tab stop, so keyboard users Tab past the whole bar in one step and rely on
            // core/menu_navigation's arrow-key handling to move within it.
            tabIndex={selected ? 0 : -1}
            // React owns this pill's active state via the `selected` prop above; without this,
            // core/menu_navigation's legacy click handler forces aria-current onto the clicked
            // pill regardless of whether the click actually navigates anywhere (e.g. a stubbed-out
            // href in tests, or any other no-op link), leaving the old and new active items both
            // (or neither) marked correctly. TabPill and DropdownItems' istablist items already opt
            // out the same way.
            data-disableactive="true"
        />
    );
};

/**
 * Default for the `measuredclass` prop: the CSS class toggled on the React mount-point container
 * once the overflow split has settled.
 */
const MEASURED_CLASS = 'secondarynav-measured';

/**
 * Root component for the navigation pill.
 *
 * @param props Component props.
 * @param props.items The top-level navigation nodes.
 * @param props.morelabel The localised label for the "More" overflow dropdown.
 * @param props.istablist Whether the nav is rendered as an ARIA tablist.
 * @param props.navbarstyle Extra class for the <ul>, e.g. "navbar-nav" for the primary navigation.
 * @param props.measuredclass Class added to the mount point once the overflow split has settled.
 * @param props.navlabel Accessible name for the navigation landmark wrapping the menu.
 * @param props.gobacklabel The label of the "Go back" links in the smart menu carousels.
 * @returns The rendered navigation pill.
 */
export default function Nav(
    {items, morelabel, istablist, navbarstyle, measuredclass = MEASURED_CLASS, navlabel, gobacklabel = ''}: NavProps,
) {
    const menuRef = useRef<HTMLUListElement>(null);

    // Boost Union modification: Keep the dropdowns of the top-level pills within the viewport. Bootstrap does not
    // position dropdowns within a navbar with Popper, so a dropdown of a pill near the end of the viewport would
    // overflow it (at the right edge in LTR mode, at the left edge in RTL mode). Align such a dropdown to the end
    // edge of its pill instead as soon as it is shown.
    // Additionally, keep the "More" dropdown free of horizontal overflow. The smart menus which are nested within the
    // "More" dropdown expand in place and can make it taller than the viewport, so that it gets a vertical scrollbar
    // (Moodle core limits its height, see theme/boost/scss/moodle/undo.scss). Some browsers do not widen the dropdown
    // (whose width is derived from its content) by the width of this scrollbar but squeeze the content instead, which
    // then overflows horizontally and shows a horizontal scrollbar as well. Whenever the content of the "More" dropdown
    // changes (a nested smart menu is opened or closed, a carousel inside it slides) or the viewport is resized, the
    // dropdown is widened by the horizontal overflow, if there is any.
    useEffect(() => {
        const menu = menuRef.current;
        if (!menu) {
            return undefined;
        }

        const handleShown = (event: Event) => {
            const toggle = event.target;
            if (!(toggle instanceof HTMLElement) || !toggle.parentElement?.matches('li.nav-item')) {
                return;
            }
            const dropdown = toggle.parentElement.querySelector<HTMLElement>(':scope > .dropdown-menu');
            if (!dropdown) {
                return;
            }
            dropdown.classList.remove('dropdown-menu-end');
            const rect = dropdown.getBoundingClientRect();
            if (rect.right > document.documentElement.clientWidth || rect.left < 0) {
                dropdown.classList.add('dropdown-menu-end');
            }
        };

        const fitMoreMenu = () => {
            const dropdown = menu.querySelector<HTMLElement>(':scope > li.dropdownmoremenu > .dropdown-menu');
            if (!dropdown) {
                return;
            }
            dropdown.style.width = '';
            if (!dropdown.classList.contains('show')) {
                return;
            }
            const overflow = dropdown.scrollWidth - dropdown.clientWidth;
            if (overflow > 0) {
                dropdown.style.width = `${dropdown.offsetWidth + overflow}px`;
            }
        };

        menu.addEventListener('shown.bs.dropdown', handleShown);
        menu.addEventListener('shown.bs.dropdown', fitMoreMenu);
        menu.addEventListener('hidden.bs.dropdown', fitMoreMenu);
        menu.addEventListener('slid.bs.carousel', fitMoreMenu);
        window.addEventListener('resize', fitMoreMenu);
        return () => {
            menu.removeEventListener('shown.bs.dropdown', handleShown);
            menu.removeEventListener('shown.bs.dropdown', fitMoreMenu);
            menu.removeEventListener('hidden.bs.dropdown', fitMoreMenu);
            menu.removeEventListener('slid.bs.carousel', fitMoreMenu);
            window.removeEventListener('resize', fitMoreMenu);
        };
    }, []);

    // Bootstrap's Tab component (data-bs-toggle="tab") can activate a tab client-side. React never hears about
    // that on its own. Track the href Bootstrap actually activates and use it to override the server's `active` flags.
    // Seeded from location.hash on mount (rather than left null until the first shown.bs.tab event) because the
    // browser never sends the hash to the server.
    const [activeOverrideHref, setActiveOverrideHref] = useState<string | null>(() => {
        if (!istablist) {
            return null;
        }
        const hash = window.location.hash;
        return hash && hasNodeWithHref(items, hash) ? hash : null;
    });

    useEffect(() => {
        if (!istablist) {
            return undefined;
        }

        const handleShown = (event: Event) => {
            const target = event.target;
            if (!(target instanceof HTMLElement) || !menuRef.current?.contains(target)) {
                return;
            }
            const href = target.getAttribute('href');
            if (href && href !== '#') {
                setActiveOverrideHref(href);
            }
        };

        document.addEventListener('shown.bs.tab', handleShown);
        return () => document.removeEventListener('shown.bs.tab', handleShown);
    }, [istablist]);

    const effectiveItems = istablist && activeOverrideHref ? withActiveHref(items, activeOverrideHref) : items;

    // Dividers are a dropdown-only concept (see DropdownItems); the server side export already
    // drops them at the top level, but guard here too so one could never render as a bare pill.
    const toplevel = effectiveItems.filter((item) => !item.divider);
    const forced = toplevel.filter((item) => item.forceintomoremenu);
    const rest = toplevel.filter((item) => !item.forceintomoremenu);
    // Boost Union modification: Smart menus which are configured to be kept outside of the "More" menu
    // never take part in the overflow split.
    const collapsible = rest.filter((item) => !item.forceout);

    // The landmark, when one is rendered. The measurement below sizes the menu against the React
    // mount point, so it must resolve the container from whichever element is outermost here
    // rather than from the <ul>, whose parent is the landmark once there is one.
    const landmarkRef = useRef<HTMLElement>(null);
    const [autoOverflowCount, setAutoOverflowCount] = useState(0);
    const [measured, setMeasured] = useState(false);

    // Forces a re-render (and a fresh measurement pass) when the ResizeObserver fires but
    // autoOverflowCount doesn't itself need to change, e.g. the container grew and the current
    // split still fits.
    const [, forceRemeasure] = useState(0);

    // Bounded convergence loop bookkeeping for the measurement effect below. Refs, not state,
    // because they must never themselves trigger a re-render. Reset at the start of each fresh
    // "settle cycle": when `items` changes (see itemsKey below) or the container is resized.
    // - stepsRef: steps taken this cycle, capped at `2 * collapsible.length + 2` (see effect body) so the
    //   loop can never run forever.
    // - lastActionRef: what the last step did ('grow' | 'shrink' | null), to tell a genuine "still
    //   doesn't fit, grow more" apart from "that shrink attempt didn't pay off, revert it".
    // - shrinkExhaustedRef: once a shrink attempt is reverted this cycle, don't try another until
    //   the next cycle, otherwise it would just bounce between the two states forever.
    const stepsRef = useRef(0);
    const lastActionRef = useRef<'grow' | 'shrink' | null>(null);
    const shrinkExhaustedRef = useRef(false);

    // Lets the measurement effect (which has no dependency array) detect an `items` prop change
    // from inside itself.
    const itemsKey = items.map((item) => item.key).join(' ');
    const prevItemsKeyRef = useRef(itemsKey);

    // Keyboard arrow-key/Home/End navigation, via the existing core/menu_navigation module.
    // Bootstrap's data-bs-toggle="tab"/"dropdown" APIs handle panel-switching/opening separately.
    // Legacy moremenu.js called this unconditionally for every nav, not just istablist ones: it's
    // also what makes Space (not just Enter) activate a pill/menuitem.
    useEffect(() => {
        if (!menuRef.current) {
            return undefined;
        }

        let cancelled = false;

        requireAsync<(menu: HTMLElement) => void>('core/menu_navigation').then((menuNavigation) => {
            if (!cancelled && menuRef.current) {
                menuNavigation(menuRef.current);
            }
            return undefined;
        });

        return () => {
            cancelled = true;
        };
    }, []);

    // Boost Union modification: Initialize the Bootstrap tooltips of the smart menu items after every commit
    // (deliberately no dependency array), as the items are re-mounted whenever they move between the navigation
    // bar and the "More" dropdown. theme_boost/loader only initializes the tooltips which exist on page load.
    // The tooltips of the items which have been unmounted in the meantime are disposed (this also removes a tooltip
    // which is still shown for an item which has just been unmounted), as are all tooltips when the navigation itself
    // is unmounted. The map holds the function which disposes the tooltip of each initialized trigger (see initTooltip).
    const tooltipsRef = useRef(new Map<HTMLElement, TooltipDisposer>());
    useEffect(() => {
        const tooltips = tooltipsRef.current;
        tooltips.forEach((dispose, trigger) => {
            if (!trigger.isConnected) {
                dispose();
                tooltips.delete(trigger);
            }
        });
        const root = landmarkRef.current ?? menuRef.current;
        root?.querySelectorAll<HTMLElement>(TOOLTIP_TRIGGER_SELECTOR).forEach((trigger) => {
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

    // Measures the real DOM after every commit (deliberately no dependency array) and nudges
    // autoOverflowCount by one step per pass until the split converges. Runs pre-paint, so
    // intermediate states are never visible; measuredclass/opacity cover the first convergence.
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

        const menu = menuRef.current;
        const container = (landmarkRef.current ?? menu)?.parentElement;
        if (!menu || !container) {
            // Fail open: show the tab bar unmeasured rather than hide it forever.
            setMeasured(true);
            return;
        }

        const reveal = () => {
            if (!measured) {
                container.classList.add(measuredclass);
            }
        };

        const wrapped = menu.offsetHeight > container.offsetHeight;

        // 2 * collapsible.length + 2 covers the worst case of growing all the way from 0, or shrinking
        // all the way back down plus one revert step, so this is guaranteed to stop.
        const bound = (2 * collapsible.length) + 2;

        if (wrapped) {
            if (lastActionRef.current === 'shrink') {
                // The previous shrink didn't pay off: revert it, don't try shrinking again this cycle.
                lastActionRef.current = null;
                shrinkExhaustedRef.current = true;
                if (stepsRef.current < bound) {
                    stepsRef.current += 1;
                    setAutoOverflowCount((count) => count + 1);
                    return;
                }
            } else if (autoOverflowCount < collapsible.length && stepsRef.current < bound) {
                stepsRef.current += 1;
                lastActionRef.current = 'grow';
                setAutoOverflowCount((count) => count + 1);
                return;
            }

            reveal();
            setMeasured(true);
            return;
        }

        if (autoOverflowCount > 0 && !shrinkExhaustedRef.current && stepsRef.current < bound) {
            // Try recovering one item back out of overflow, in case there's now room for it.
            stepsRef.current += 1;
            lastActionRef.current = 'shrink';
            setAutoOverflowCount((count) => Math.max(count - 1, 0));
            return;
        }

        lastActionRef.current = null;
        reveal();
        setMeasured(true);
    });

    // Re-measures whenever the space available to the menu may have changed. Both triggers are
    // needed:
    //
    // - The ResizeObserver catches width changes that don't come from the viewport, e.g. a drawer
    //   opening beside the navigation. It observes the container, not the <ul>, so it doesn't
    //   fire from the measurement effect's own re-renders.
    // - The window resize listener, as legacy moremenu.js used, catches viewport changes that
    //   leave the container's box untouched. The primary navigation's mount point is a
    //   shrink-to-fit flex item, so once items have collapsed into "More" it is only as wide as
    //   what's left: widening the window resizes it by nothing, and they would never come back out.
    useEffect(() => {
        const remeasure = () => {
            stepsRef.current = 0;
            lastActionRef.current = null;
            shrinkExhaustedRef.current = false;
            forceRemeasure((tick) => tick + 1);
        };

        window.addEventListener('resize', remeasure);

        const container = (landmarkRef.current ?? menuRef.current)?.parentElement;
        let observer: ResizeObserver | null = null;
        if (container && typeof ResizeObserver !== 'undefined') {
            observer = new ResizeObserver(remeasure);
            observer.observe(container);
        }

        return () => {
            window.removeEventListener('resize', remeasure);
            observer?.disconnect();
        };
    }, []);

    const visibleCount = Math.max(collapsible.length - autoOverflowCount, 0);
    // Boost Union modification: The visible items keep their original order, the smart menus which are kept
    // outside of the "More" menu are always visible.
    const visibleCollapsible = new Set(collapsible.slice(0, visibleCount));
    const visible = rest.filter((item) => item.forceout || visibleCollapsible.has(item));
    // Auto-collapsed items first, then always-forced ones, matching legacy moremenu.js ordering.
    const overflow = [...collapsible.slice(visibleCount), ...forced];

    // Matches secondarymoremenu.mustache's NonJS fallback: every <li> is role="none" regardless
    // of istablist, and the <ul> itself is "menubar" rather than roleless when not a tablist, so
    // that Behat helpers like behat_navigation::select_on_administration_page() (which look up
    // //ul[@role='menubar']/li/a[...] for non-tablist navs) keep working under JS.
    const itemRole = 'none';

    // Keyboard movement along an istablist bar, with manual activation: the arrow keys and Home/End only move
    // focus, and Enter or Space activates the focused tab. Bootstrap's own Tab keydown handler can't be left to
    // do this: it activates on every arrow press, skips the "More" toggle (a .dropdown-toggle) but counts the
    // overflowed tabs inside the closed menu, so an arrow press can activate a hidden tab. It can't take focus,
    // which is dropped to the page start. Move between the visible tabs and the toggle instead.
    // Keys pressed inside the dropdown menu itself are left to Bootstrap.
    //
    // Bootstrap registers its delegated handlers in the capture phase on `document`, so neither a React
    // onKeyDown nor a listener on the menu ever sees the event first. Listen on `window`, which sits before
    // `document` in the capture path, and stop the event there.
    useEffect(() => {
        if (!istablist) {
            return undefined;
        }

        const keys = ['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Home', 'End', ' '];
        const handleKeyDown = (event: globalThis.KeyboardEvent) => {
            const target = event.target;
            if (!keys.includes(event.key) || !(target instanceof HTMLElement) || target.closest('.dropdown-menu')) {
                return;
            }

            // Up/Down/Space on a dropdown toggle open its menu (theme_boost/aria.js and Bootstrap's Dropdown).
            if (target.matches('[data-bs-toggle="dropdown"]') && !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
                return;
            }

            const stops = Array.from(menuRef.current?.querySelectorAll<HTMLElement>(':scope > li > a[role="tab"]') ?? [])
                .filter((stop) => !stop.closest('.d-none'));
            const index = stops.indexOf(target);
            if (index === -1) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (event.key === ' ') {
                // Enter already clicks an <a>, Space does not.
                target.click();
                return;
            }

            let next: HTMLElement;
            if (event.key === 'Home') {
                next = stops[0];
            } else if (event.key === 'End') {
                next = stops[stops.length - 1];
            } else {
                const step = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : -1;
                next = stops[(index + step + stops.length) % stops.length];
            }
            next.focus();
        };

        window.addEventListener('keydown', handleKeyDown, true);
        return () => window.removeEventListener('keydown', handleKeyDown, true);
    }, [istablist]);

    const moreItem = (
        <PillDropdownToggle label={morelabel} selected={overflow.some(isNodeActive)} istablist={istablist}>
            <div className="dropdown-menu dropdown-menu-start" data-region="moredropdown">
                <DropdownItems items={overflow} istablist={istablist} submenus gobacklabel={gobacklabel} />
            </div>
        </PillDropdownToggle>
    );

    const menu = (
        <ul
            ref={menuRef}
            className={['nav', 'more-nav', navbarstyle].filter(Boolean).join(' ')}
            role={istablist ? 'tablist' : 'menubar'}
        >
            {visible.map((item) => {
                const isSubmenuTrigger = item.showchildreninsubmenu && item.children.length > 0;
                return (
                    <li
                        key={item.key}
                        role={itemRole}
                        className={joinClasses(
                            'nav-item d-flex align-items-center',
                            isSubmenuTrigger && 'dropdown',
                            item.wrapperclasses,
                        )}
                        data-key={item.key}
                    >
                        {renderPill(item, istablist, gobacklabel)}
                    </li>
                );
            })}
            <li
                role={itemRole}
                className={`nav-item d-flex align-items-center dropdown dropdownmoremenu${overflow.length === 0 ? ' d-none' : ''}`}
            >
                {moreItem}
            </li>
        </ul>
    );

    if (!navlabel) {
        return menu;
    }

    // The landmark is rendered here rather than around the mount point, so that the menu carries
    // its own semantics wherever it is mounted. It stands in for the <nav> in the NonJS fallback
    // of secondarymoremenu.mustache, which this replaces on hydration, so the page has exactly one
    // navigation landmark either way.
    //
    // Deliberately unstyled: the fallback's wrapper carries "moremenu navigation", but
    // .secondary-navigation .navigation sets padding on a descendant match, so repeating those
    // classes inside the mount point (which already has .navigation) would indent the menu twice.
    return (
        <nav ref={landmarkRef} aria-label={navlabel}>
            {menu}
        </nav>
    );
}
