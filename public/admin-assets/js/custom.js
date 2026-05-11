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
