<div style="background-color:<?= config_item('email_footer_background_color')?>;">
    <div style="Margin: 0 auto;min-width: 320px;max-width: 500px;width: 500px;width: calc(19000% - 98300px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
        <div style="border-collapse: collapse;display: table;width: 100%;">
            <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="background-color:<?= config_item('email_footer_background_color')?>;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width: 500px;"><tr class="layout-full-width" style="background-color:transparent;"><![endif]-->
            <!--[if (mso)|(IE)]><td align="center" width="500" style=" width:500px; padding-right: 0px; padding-left: 0px; border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;" valign="top"><![endif]-->
            <div class="col num12" style="min-width: 320px;max-width: 500px;width: 500px;width: calc(18000% - 89500px);background-color: transparent;">
                <!--[if (!mso)&(!IE)]><!-->
                <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent;">
                    <!--<![endif]-->
                    <div style="background-color: transparent; display: inline-block!important; width: 100% !important;">
                        <div style="Margin-top:30px; Margin-bottom:30px;">
                            <div align="center" style="Margin-right: 10px; Margin-left: 10px; Margin-bottom: 10px;">
                                <div style="line-height:10px;font-size:1px">&nbsp;</div>
                                <div style="display: table; max-width:464px;">
                                    <!--[if (mso)|(IE)]><table width="464" align="center" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; mso-table-lspace: 0pt;mso-table-rspace: 0pt; width:464px;"><tr><td width="37" style="width:37px;" valign="top"><![endif]-->
                                    <?php foreach (config_item('email_social_links') as $social):?>
                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="32" height="32" style="border-spacing: 0;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;Margin-right: 5px">
                                        <tbody>
                                            <tr style="vertical-align: top">
                                                <td align="left" valign="middle" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
                                                    <a href="<?= $social['url'] ?>" title="<?= ucwords($social['name']) ?>" target="_blank">
                                                        <img src="<?= base_url().'assets/img/email/'.config_item('email_social_style').'/'.$social['name'].'.png'?>" alt="<?= ucwords($social['name']) ?>" title="<?= ucwords($social['name']) ?>" width="32" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block;border: none;height: auto;float: none;max-width: 32px !important">
                                                    </a>
                                                    <div style="line-height:5px;font-size:1px">&nbsp;</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--[if (mso)|(IE)]></td><td width="37" style="width:37px;" valign="top"><![endif]-->
                                    <?php endforeach;?>
                                    <!--[if (mso)|(IE)]></td></tr></table><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>&nbsp;</td></tr></table><![endif]-->
                                </div>
                            </div>
                            <!--[if !mso]><!-->
                            <div style="Margin-right: 10px; Margin-left: 10px;">
                                <!--<![endif]-->
                                <div style="line-height: 15px; font-size: 1px">&nbsp;</div>
                                <!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px;"><![endif]-->
                                <div style="font-size:12px;line-height:18px;color:<?= config_item('email_footer_font_color')?>;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;text-align:left;">
                                    <p style="margin: 0;font-size: 14px;line-height: 21px;text-align: center"><?= config_item('app_name').' - '.config_item('app_motto')?></p>
                                    <p style="margin: 0;font-size: 14px;line-height: 21px;text-align: center"><?= config_item('company_address')?></p>
                                </div>
                                <!--[if mso]></td></tr></table><![endif]-->
                                <div style="line-height: 10px; font-size: 1px">&nbsp;</div>
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
<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
</div>
</body>

</html>
