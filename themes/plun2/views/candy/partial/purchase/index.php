<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/scripts/candy.js?t='.time(), CClientScript::POS_END);
?>
<div id="popup_inCandy" class="popup_general" style="display:none;">
            <div class="content clearfix">
                <p>Thực hiện giao dịch với <b>10</b> candy</p>
                <div class="frmInCandy">
                    <p>Bạn hiện chưa có candy. Nạp bằng: </p>
                    <ul class="txtTabs">
                        <li class="inCartMobi active"><a rel="#cardMobiInput" href="#"><ins class="icon_common"></ins>Thẻ cào</a></li>
                        <li class="inBank"><a rel="#nLuongInput" href="#"><ins class="icon_common"></ins></a></li>
                    </ul>
                    <div class="wrapTabsShow">
                        <div id="cardMobiInput" class="itemTab" style="display:block;">
                            <div class="el_row_frm">
                                <label>Mạng di động:</label>
                                <div class="el_frm">
                                    <div class="select_style">
                                        <select class="virtual_form">
                                          <option selected="selected">Chọn mạng</option>
                                          <option>Chọn mạng</option>
                                      </select>                            
                                      <span class="txt_select"><span class="language_preference">Chọn mạng</span></span> <span class="btn_combo_down"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="el_row_frm">
                            <label>Mệnh giá thẻ:</label>
                            <div class="el_frm">
                                <div class="select_style">
                                    <select class="virtual_form">
                                      <option selected="selected">20.000</option>
                                      <option>20.000</option>
                                  </select>                            
                                  <span class="txt_select"><span class="language_preference">20.000</span></span> <span class="btn_combo_down"></span>
                              </div>
                          </div>
                      </div>
                      <div class="el_row_frm">
                        <label>Số Candy tương ứng:</label>
                        <div class="el_frm">
                            <input type="text">
                        </div>
                    </div>
                    <div class="el_row_frm">
                        <label>Mã thẻ cào:</label>
                        <div class="el_frm">
                            <input type="text">
                        </div>
                    </div>
                    <div class="right">
                        <button class="btn-gray changeBg">Hủy</button>
                        <button class="btnFrmSubmitPurple changeBg txtBlock" onclick="Candy.show_more_newsfeed('vinhnguyen', 1);">Nạp</button>
                    </div>
                </div>
                <div id="nLuongInput" class="itemTab" style="display:none;">
                    <img src="images/nldb_s_150.png" />
                    <ul class="list-card-tt">
                        <li>
                            <label class="icon-common icon-bidv" for="bidv_b"></label>
                            <div class="radio_ui">
                                <input id="bidv_b" type="radio" name="check-nh-tt" value="BIDV">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-vcb" for="vcb_b"></label>
                            <div class="radio_ui">
                                <input id="vcb_b" type="radio" name="check-nh-tt" value="VCB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-da"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="DAB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-tcb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="TCB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-mb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="MB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-shb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="SHB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-vib"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="VIB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-vin"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="ICB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-exim"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="EXB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-acb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="ACB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-hd"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="HDB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-mrt"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="MSB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-navi"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="NVB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-va"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="VAB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-vp"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="VPB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-sc"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="SCB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-ob"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="OJB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-pg"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="PGB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-gb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="GPB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-ari"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="AGB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-sb"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="SGB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-na"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="NAB">
                            </div>  
                        </li>
                        <li>
                            <label class="icon-common icon-ba"></label>
                            <div class="radio_ui">
                                <input type="radio" name="check-nh-tt" value="BAB">
                            </div>  
                        </li>
                    </ul>
                    <div class="right">
                        <button class="btn-gray changeBg">Hủy</button>
                        <button class="btnFrmSubmitPurple changeBg txtBlock">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>