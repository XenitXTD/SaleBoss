@if(!is_null($letter->next_place))
            <button
                type="button"
                class="btn btn-xs margin-right btn-info operation-margin"
                delete-url="{{URL::to('letters/action/' . $letter->letter_id)}}"
                onclick="Common.setDeleteURL(this,'#message_form')"
                data-toggle="modal"
                data-target="#messageModal"><i class="ace-icon fa fa-reply icon-only"></i> تایید و ارسال نامه
            </button>

        <!--Message Modal -->
        <div class="modal slide-down" id="messageModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="messageModal" aria-hidden="true">
          <div class="modal-dialog .modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ارسال پیام تایید</h4>
              </div>
              <div class="modal-body">
                        {{Form::open(array(
                                'url'			=>	'#',
                                'method'		=>	'put',
                                'id'			=>	'message_form',
                                'class'			=>	'message_form'
                            ))}}
                           <input type="hidden" name="letterId" value="{{$letter->letter_id}}">
                           <input type="hidden" name="actionType" value="1">
                           <input type="hidden" name="destinationId" value="{{$letter->destinationP->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label Nassim Nassim700 NassimTitle">متن پیام: </label>
                                <textarea class="form-control" name="logMessage" rows="10"></textarea>
                            </div>
                        </div>
                        <br>
                    {{Form::close()}}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#message_form')">ارسال پیام</button>
                <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
              </div>
            </div>
          </div>
        </div>
    @endif
    @if(!is_null($letter->prev_place))
              <button
                    type="button"
                    class="btn btn-xs margin-right operation-margin"
                    delete-url="{{URL::to('letters/action/' . $letter->letter_id)}}"
                    onclick="Common.setDeleteURL(this,'#message_back_form')"
                    data-toggle="modal"
                    data-target="#messageBackModal"><i class="ace-icon fa fa-undo bigger-120"></i> برگشت
                </button>

            <!--Message Modal -->
            <div class="modal slide-down" id="messageBackModal" tabindex="-1" style="overflow-y:hidden;" role="dialog" aria-labelledby="messageModal" aria-hidden="true">
              <div class="modal-dialog .modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title Nassim Nassim700" id="myModalLabel">ارسال پیام برگشت</h4>
                  </div>
                  <div class="modal-body">
                            {{Form::open(array(
                                    'url'			=>	'#',
                                    'method'		=>	'put',
                                    'id'			=>	'message_back_form',
                                    'class'			=>	'message_form'
                                ))}}
                    <input type="hidden" name="letterId" value="{{$letter->letter_id}}">
                   <input type="hidden" name="actionType" value="2">
                   <input type="hidden" name="destinationId" value="{{$letter->destinationP->id}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label Nassim Nassim700 NassimTitle">متن پیام: </label>
                                    <textarea class="form-control" name="logMessage" rows="10"></textarea>
                                </div>
                            </div>
                            <br>
                        {{Form::close()}}
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning pull-left radius" style="margin-right: 10px;" onclick="Common.submitUpdateForm('#message_back_form')">ارسال پیام</button>
                    <button type="button" class="btn btn-sm btn-default pull-left radius" data-dismiss="modal">بستن</button>
                  </div>
                </div>
              </div>
            </div>
    @endif

