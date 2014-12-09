
          <div>
             <div class="table-header Nassim Nassim700 NassimTitle panelColor" style="padding-right: 10px;" >
              <i class="fa fa-list"></i> لیست لیدهای من
            </div>
                <div class="form-inline">
                    <table class="table table-striped table-hover">
                           <thead>
                               <tr role="row">
                                  <th style="padding:13px; width: 90px;"><i class="fa fa-flag"></i> شناسه</th>
                                  <th><i class="fa fa-user"></i> نام شخص یا شرکت</th>
                                  <th class="text-center" style="width: 130px;"><i class="fa fa-mobile"></i> شماره تماس</th>
                                  <th class="text-center"><i class="fa fa-tag"></i> زمینه فعالیت</th>
                                  <th><i class="fa fa-file-text"></i> توضیحات</th>
                                  <th class="text-center"><i class="fa fa-star"></i> اهمیت </th>
                                  <th>وضعیت</th>
                                  <th style="width: 130px;">به یاد آوری در</th>
                                  <th class="languageLeft">عملیات</th>
                              </tr>
                           </thead>
                           <tbody class="tableFontSize12">
                              <tr class="inline-form-tr">
                              				{{Form::open([
                              					'url'		=>	'me/leads',
                              					'method'	=>	'post',
                              					'id'		=>	'lead-store-form'
                              				])}}
                              				<td>__</td>
                              				<td>{{Form::text('name',null,['placeholder' => 'نام شخص یا شرکت','class' => 'form-control bulkable resettable', 'style' => 'width: 100%'])}}</td>
                              				<td>{{Form::text('phone',null,['placeholder' => 'شماره تماس','class' => 'form-control languageLeft bulkable resettable', 'style' => 'width: 100%'])}}</td>
                              				<td>{{Form::select('tag',SaleBoss\Models\Tag::getTagList(),182,['class' => 'form-control stable', 'style' => 'width: 100%'])}}</td>
                              				<td>{{Form::text('description',null,['placeholder' => 'توضیحات','class' => 'form-control bulkable resettable', 'style' => 'width: 100%'])}}</td>
                              				<td>{{Form::select( 'priority',['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], 1,['class' => 'form-control languageLeft bulkable resettable', 'style' => 'width: 100%'])}}</td>
                              				<td> {{Form::select('status',$opiloConfig['lead_status'],0,['class' => 'form-control stable', 'style' => 'width: 100%'])}}</td>
                              				<td>{{Form::text('remind_at',null,['class' => 'form-control resettable', 'placeholder' => 'به یادآوری در چندروز بعد؟', 'style' => 'width: 100%'])}}</td>
                              				<td>
                              					<button type="submit" data-loading-text='<i class="fa fa-spinner"></i>' class="btn radius btn-xs operation-margin btn-success submit_button"><i class="fa fa-plus"></i></button>
                              					<button type="reset" class="btn btn-xs btn-warning radius"><i class="fa fa-list"></i></button>
                              				</td>
                              				{{Form::close()}}
                              			</tr>
                              			<tr style="display: none">
                              			    <td>{{Form::text('name',null,['placeholder' => 'نام شخص یا شرکت','class' => 'form-control bulkable resettable', 'style' => 'width: 100%'])}}</td>
                                            <td>{{Form::text('phone',null,['placeholder' => 'شماره تماس','class' => 'form-control languageLeft bulkable resettable', 'style' => 'width: 100%'])}}</td>
                                            <td class="tags">{{Form::select('tag',SaleBoss\Models\Tag::getTagList(),['class' => 'form-control stable', 'style' => 'width: 100% !important'])}}</td>
                                            <td>{{Form::text('description',null,['placeholder' => 'توضیحات','class' => 'form-control bulkable resettable', 'style' => 'width: 100%'])}}</td>
                                            <td class="priorities">{{Form::select( 'priority',['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5'], 1,['class' => 'form-control languageLeft bulkable resettable', 'style' => 'width: 100%'])}}</td>
                                            <td class="statuses"> {{Form::select('status',$opiloConfig['lead_status'],0,['class' => 'form-control stable', 'style' => 'width: 100%'])}}</td>
                                            <td>{{Form::text('remind_at',null,['class' => 'form-control resettable', 'placeholder' => 'به یادآوری در چندروز بعد؟', 'style' => 'width: 100%'])}}</td>
                              			</tr>
                                          @foreach($list as $lead)
                                              <tr @if ($lead->new_lead === 1) style="background-color: #F4726D;" @endif>
                                                  <td>#{{$lead->id}}</td>
                                                  <td>{{$lead->name}}</td>
                                                  <td class="text-center" style="direction: ltr">{{$lead->phones->first()->number}}</td>
                                                  <td class="text-center" style="direction: ltr">{{$lead->tags->last()->name}}</td>
                                                  <td>{{empty($lead->description) ? 'ندارد' : softTrim($lead->description,50)}}</td>
                                                  <td class="text-center">
                                                      @for($i=1;$i<=$lead->priority;$i++)
                                                          <i style="color:#CC9900" class="fa fa-star"></i>
                                                      @endfor
                                                  </td>
                                                  <td>
                                                      <span class="label arrowed label-<?php print statusClass($lead->status)?>">
                                                          {{$opiloConfig['lead_status'][$lead->status]}}
                                                      </span>
                                                  </td>
                                                  <td>
                                                      @if(!is_null($lead->remind_at))
                                                          <i class="fa fa-calendar"></i> {{$lead->jalaliDate('remind_at')}} ({{$lead->jalaliAgoDate('remind_at')}})
                                                      @else
                                                      	ندارد
                                                      @endif
                                                  </td>
                                                  <td class="languageLeft">
                              						@include('admin.pages.lead.partials._my_list_operation')
                                                  </td>
                                              </tr>
                                          @endforeach
                          </tbody>
                          <tfoot>
                                <tr>
                                    <td colspan="9">
                                        {{$list->appends(Input::except('page'))->links()}}
                                    </td>
                                </tr>
                          </tfoot>
                    </table>
                </div>
          </div>
