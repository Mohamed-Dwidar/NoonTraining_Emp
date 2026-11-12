$(document).ready(function () {

    ////////Search &  Filter & Sort//////
    var searchSortFilterParams = '';
    $('#srchInput').keypress(function (e) {
        var key = e.which;
        if (key == 13) // the enter key code
        {
            $('button[class="srch-icon"]').click();
            return false;
        }
    });

    $(".srch-icon").on('click', function () {
        searchSortFilterParams = collectSearchSortFilterParams();
        document.location.href = url + searchSortFilterParams;
    });
    $(".header-search .clear").on('click', function () {
        $('#srchInput').val('');
        searchSortFilterParams = collectSearchSortFilterParams();
        document.location.href = url + searchSortFilterParams;
    });

    $('.filter-item').click(function (event) {
        event.preventDefault();
        var e = $(this);
        $('#fltr_val').val(e.data('val'));
        searchSortFilterParams = collectSearchSortFilterParams();
        document.location.href = url + searchSortFilterParams;
    });

    $('.filter-item-brnch').click(function (event) {
        event.preventDefault();
        var e = $(this);
        $('#fltr_brnch_val').val(e.data('val'));
        searchSortFilterParams = collectSearchSortFilterParams();
        document.location.href = url + searchSortFilterParams;
    });

    

    $('.sort-item').click(function (event) {
        event.preventDefault();
        var e = $(this);
        $('#sort_val').val(e.data('val'));
        searchSortFilterParams = collectSearchSortFilterParams();
        document.location.href = url + searchSortFilterParams;
    });



    $('#export-excel').click(function(){
        searchSortFilterParams = collectSearchSortFilterParams();
        // alert(url + searchSortFilterParams);
        if(searchSortFilterParams != ''){
            document.location.href = url + searchSortFilterParams + '&export=yes';
        }else{
            document.location.href = url + '?export=yes';
        }

    });

    function collectSearchSortFilterParams() {
        /////Search////
        var srchVal = $('#srchInput').val();
        var srchParam = srchVal != '' ? "srch=" + srchVal : "";
        /////////////

        ////Sort////
        var sortVal = $('#sort_val').val();
        var sortParam = (sortVal != '' && sortVal != 'no') ? "srt=" + sortVal : "";
        //////////

        ////Filter////
        var fltrVal = $('#fltr_val').val();
        var filterParam = (fltrVal != null && fltrVal != '' && fltrVal != 'no') ? "fltr=" + fltrVal : "";
        ////////// 

        ////Filter Branch////
        var fltrBranchVal = $('#fltr_brnch_val').val();
        var filterBranchParam = (fltrBranchVal != null && fltrBranchVal != '' && fltrBranchVal != 'no') ? "brnch=" + fltrBranchVal : "";
        ////////// 


        var finalParams = "";
        if (srchParam != "") {
            finalParams += (srchParam + "&");
        }
        if (sortParam != "") {
            finalParams += (sortParam + "&");
        }
        if (filterParam != "") {
            finalParams += (filterParam + "&");
        }
        if (filterBranchParam != "") {
            finalParams += (filterBranchParam + "&");
        }

        finalParams = finalParams.replace(/&\s*$/, ""); //remove the last (&)
        return finalParams != "" ? "?" + finalParams : "";
    }

    ////////////////////
});

/* ---------- Responsive helpers (mobile / tablet) ---------- */
$(function () {
    var $body = $('body');

    // Mobile menu button (header) opens/closes the main menu panel
    function setMobileNav(open) {
        $body.toggleClass('mobile-nav-open', open);
        $('.mobile-nav-toggle').attr('aria-expanded', open ? 'true' : 'false')
            .find('i').toggleClass('fa-bars', !open).toggleClass('fa-times', open);
    }
    $(document).on('click', '.mobile-nav-toggle', function (e) {
        e.stopPropagation();
        setMobileNav(!$body.hasClass('mobile-nav-open'));
    });
    $(document).on('click', function (e) {
        if ($body.hasClass('mobile-nav-open') && !$(e.target).closest('#primary-menu, .mobile-nav-toggle').length) {
            setMobileNav(false);
        }
        // Tap outside closes any open menu dropdown
        if (!$(e.target).closest('.top-menu').length) {
            $('.top-menu li.open').removeClass('open');
        }
    });

    // Menu items with a sub-menu: open on tap (hover doesn't exist on touch screens)
    $('.top-menu > li').has('> ul.dropdown').addClass('has-dropdown').children('a').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('open').siblings().removeClass('open');
    });

    // Every table scrolls sideways inside its card instead of stretching the page
    $('.content-wrapper table').each(function () {
        var $t = $(this);
        if (!$t.hasClass('table-responsive') && !$t.closest('.table-responsive').length) {
            $t.wrap('<div class="table-responsive"></div>');
        }
    });
});
