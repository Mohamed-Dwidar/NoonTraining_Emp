<!-- Loading Modal -->
<div class="modal fade text-left" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalProviderel35"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 180px;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <div class="col-md-12" id="saving">
                    <img src="{{ asset('images/loading-icon.gif') }}" style="width: 72px;margin:42px">
                </div>

                <div class="col-md-12" id="succSaved" style="display:none">
                    <img src="{{ asset('images/saved.png') }}" style="width: 75px;margin:20px">
                    <br />
                    <span style="font-weight:bold;font-size:17px" id="savedMsg">Successfully Saved </span>
                </div>


            </div>
        </div>
    </div>
</div>
<!------------------->
<!-- Error Modal -->
<div class="modal fade text-left" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalProviderel35"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 180px;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <div class="col-md-12" id="errReq">
                    <img src="{{ asset('images/error-flat.png') }}" style="width: 72px;margin:20px">
                    <br />
                    <span style="font-weight:bold;font-size:17px" id="errMsg">Err</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------->ZZ


@if(Request::segment(1) == 'admin' && Request::segment(2) == 'students')

@endif