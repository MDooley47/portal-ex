import DatatableManager from "./DatatableManager";

export default class Setup {
    constructor(additional) {
        Setup.setupPostLinks();
        Setup.setupSelect2();
        Setup.setupDataTable();

        if (additional !== undefined) additional();
    }

    /**
     * Sends post request before following links
     * with the class .post
     * Sends slug to action assuming /action[/:slug]
     *
     * @return void
     */
    static setupPostLinks() {
        $('.post').click(e => {
            let href = $(this).attr('href');
            let hrefArr = href.split('/');
            let slug = hrefArr[hrefArr.length - 2];
            let action = href.substring(0, href.length - (slug.length + 1));

            $.post(action, { 'slug': slug });
        });

        $('.post-noslug').click(e => {
            $.post($(this).attr('href'));
        });
    }

    /**
     * Enables the .select2 jQuery extension
     * for forms that use it.
     *
     * @return void
     */
    static setupSelect2() {
        $('.select2.single.grouptype').select2({
            placeholder: 'Please select a grouptype',
            allowClear: true,
            width: '20em'
        });

        $('.select2.single.tab').select2({
            placeholder: 'Please select a tab',
            allowClear: true,
            width: '20em'
        });

        $('.select2.single.group').select2({
            placeholder: 'Please select a group',
            allowClear: true,
            width: '20em'
        });
    }

    static setupDataTable() {
        window.DM = new DatatableManager([
            ['user'],
            ['group', 2],
            ['tab'],
            ['app', 2],
            ['attribute'],
            ['grouptype'],
            ['ipaddress'],
            ['ownertype'],
            ['privilege', 3],
        ]);
    }
}