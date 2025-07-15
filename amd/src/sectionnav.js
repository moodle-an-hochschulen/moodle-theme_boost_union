import jQuery from 'jquery';
import {call as fetchMany} from 'core/ajax';

export const section_nav = (courseid, section) => fetchMany([{
    methodname: 'theme_boost_union_section_nav',
    args: {
        courseid,
        section
    }
}])[0];

export const init = async (courseid, section) => {
    jQuery(document).ready(async function () {
        let result = await section_nav(courseid, section);

        let prev = jQuery('.section-navigation .prevsection');
        let next = jQuery('.section-navigation .nextsection');

        if (prev.length > 0 && result.prevlink !== null && result.prevname !== null) {
            prev.each(function () {
                let that = jQuery(this).find('a');
                if (that.length > 0) {
                    that.html(result.prevname);
                    that.attr('href', result.prevlink);
                } else {
                    jQuery(this).html("<a href='" + result.prevlink + "'>" + result.prevname + "</a>");
                }
                prev.removeClass('d-none');
            });
        } else {
            prev.hide();
        }

        if (next.length > 0 && result.nextlink !== null && result.nextname !== null) {
            next.each(function () {
                let that = jQuery(this).find('a');
                if (that.length > 0) {
                    that.html(result.nextname);
                    that.attr('href', result.nextlink);
                } else {
                    jQuery(this).html("<a href='" + result.nextlink + "'>" + result.nextname + "</a>");
                }
                next.removeClass('d-none');
            });
        } else {
            next.hide();
        }
    });
};
