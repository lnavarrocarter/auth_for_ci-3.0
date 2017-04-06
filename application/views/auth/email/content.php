<?php $this->load->view('auth/email/email_header');?>
<div style="background-color:<?= config_item('email_body_background_color')?>">
    <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;width: 500px;width: calc(19000% - 98300px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
        <div style="border-collapse: collapse;display: table;width: 100%;">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:<?= config_item('email_body_background_color')?>" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->
            <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num12" style="min-width: 320px;max-width: 500px;width: 500px;width: calc(18000% - 89500px);background-color: transparent;">
                <!--[if (!mso)&(!IE)]><!-->
                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;">
                    <!--<![endif]-->
                    <div style="background-color: transparent; display: inline-block!important; width: 100% !important;">
                        <div style="Margin-top:30px; Margin-bottom:30px;">
                            <!--[if !mso]><!-->
                            <div style="Margin-right: 0px; Margin-left: 0px;">
                                <!--<![endif]-->
                                <div style="line-height: 30px; font-size: 1px">&nbsp;</div>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 0px; padding-left: 0px;"><![endif]-->
                                <div style="font-size:12px;line-height:14px;color:<?= config_item('email_body_font_color')?>;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;">
                                    <p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><strong><span style="font-size: 28px; line-height: 33px;"><?= $title ?></span></strong></p>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <div style="line-height: 30px; font-size: 1px">&nbsp;</div>
                                <!--[if !mso]><!-->
                            </div>
                            <!--<![endif]-->
                            <!--[if !mso]><!-->
                            <div style="Margin-right: 10px; Margin-left: 10px;">
                                <!--<![endif]-->
                                <div style="line-height: 10px; font-size: 1px">&nbsp;</div>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px;"><![endif]-->
                                <div style="font-size:12px;line-height:14px;color:<?= config_item('email_body_font_color')?>;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;">
                                    <?php foreach ($paragraphs as $paragraph):?>
                                    <p style="margin: 0;font-size: 14px;line-height: 17px"><?= $paragraph ?></p>
                                    <p style="margin: 0;font-size: 14px;line-height: 16px">&nbsp;
                                        <br>
                                    </p>
                                    <?php endforeach;?>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <div style="line-height: 10px; font-size: 1px">&nbsp;</div>
                                <!--[if !mso]><!-->
                            </div>
                            <!--<![endif]-->
                            <div align="center" class="button-container center" style="Margin-right: 10px;Margin-left: 10px;">
                                <div style="line-height:15px;font-size:1px">&nbsp;</div>
                                <a href="<?= $url ?>" target="_blank" style="color: <?= config_item('body_background_color')?>; text-decoration: none;">
                                    <!--[if mso]>
      <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="" style="height:42px; v-text-anchor:middle; width:146px;" arcsize="60%" strokecolor="<?= config_item('email_brand_color')?>" fillcolor="<?= config_item('email_brand_color')?>" >
      <w:anchorlock/><center style="color:<?= config_item('body_background_color')?>; font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size:16px;">
    <![endif]-->
                                    <!--[if !mso]><!-->
                                    <div style="color: <?= config_item('body_background_color')?>; background-color: <?= config_item('email_brand_color')?>; border-radius: 25px; -webkit-border-radius: 25px; -moz-border-radius: 25px; max-width: 126px; width: 25%; border-top: 0px solid transparent; border-right: 0px solid transparent; border-bottom: 0px solid transparent; border-left: 0px solid transparent; padding-top: 5px; padding-right: 20px; padding-bottom: 5px; padding-left: 20px; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; text-align: center;">
                                        <!--<![endif]-->
                                        <span style="font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size:16px;line-height:32px;"><span style="font-size: 14px; line-height: 28px;" data-mce-style="font-size: 14px;"><?= $button ?></span></span>
                                        <!--[if !mso]><!-->
                                    </div>
                                    <!--<![endif]-->
                                    <!--[if mso]>
          </center>
      </v:roundrect>
    <![endif]-->
                                </a>
                                <div style="line-height:10px;font-size:1px">&nbsp;</div>
                            </div>
                            <!--[if !mso]><!-->
                            <div align="center" style="Margin-right: 10px;Margin-left: 10px;">
                                <!--<![endif]-->
                                <div style="line-height: 10px; font-size:1px">&nbsp;</div>
                                <!--[if (mso)|(IE)]><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px;padding-left: 10px;"><![endif]-->
                                <div style="border-top: 0px solid transparent; width:100%; font-size:1px;">&nbsp;</div>
                                <!--[if (mso)|(IE)]></td></tr></table><![endif]-->
                                <div style="line-height:10px; font-size:1px">&nbsp;</div>
                                <!--[if !mso]><!-->
                            </div>
                            <!--<![endif]-->
                        </div>
                    </div>
                    <!--[if (!mso)&(!IE)]><!-->
                </div>
                <!--<![endif]-->
            </div>
            <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
        </div>
    </div>
</div>
<?php $this->load->view('auth/email/email_footer');?>
