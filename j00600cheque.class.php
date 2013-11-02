<?php
/**
 * Core file
 * @author Vince Wooll <sales@jomres.net>
 * @version Jomres 4 
* @package Jomres
* @copyright	2005-2011 Vince Wooll
* Jomres (tm) PHP files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly, however all images, css and javascript which are copyright Vince Wooll are not GPL licensed and are not freely distributable. 
**/

// ################################################################
defined( '_JOMRES_INITCHECK' ) or die( '' );
// ################################################################

/**
#
 * Outgoing interrupt for cheque details
 #
* @package Jomres
#
 */

class j00600cheque {
	function j00600cheque($componentArgs)
		{
		// Must be in all minicomponents. Minicomponents with templates that can contain editable text should run $this->template_touch() else just return 
		$MiniComponents =jomres_getSingleton('mcHandler');
		if ($MiniComponents->template_touch)
			{
			$this->template_touchable=true; return;
			}
		$mrConfig=getPropertySpecificSettings();
		$plugin="cheque";
		$bookingdata=$componentArgs['bookingdata'];
		$output=array();
		$query="SELECT setting,value FROM #__jomres_pluginsettings WHERE prid = '".(int)$bookingdata['property_uid']."' AND plugin = '$plugin' ";
		$settingsList=doSelectSql($query);
		foreach ($settingsList as $set)
			{
			$settingArray[$set->setting]=$set->value;
			}
		$currfmt = jomres_getSingleton('jomres_currency_format');
		
		
		$output['DEPOSIT']=output_price($bookingdata['deposit_required']);
		$output['TOTAL']=output_price($bookingdata['contract_total']);
		$bal=(float)$bookingdata['contract_total']-(float)$bookingdata['deposit_required'];
		$output['BALANCE']=output_price($bal);

		$propertyAddressArray=getPropertyAddressForPrint((int)$bookingdata['property_uid']);
		$propertyContactArray=$propertyAddressArray[1];
		$propertyAddyArray=$propertyAddressArray[2];

		$output['PROP_NAME']=$propertyContactArray[0];
		$output['PROP_STREET']=$propertyContactArray[1];
		$output['PROP_TOWN']=$propertyContactArray[2];
		$output['PROP_POSTCODE']=$propertyContactArray[3];
		$output['PROP_REGION']=$propertyContactArray[4];

		$countryname=getSimpleCountry($propertyContactArray[5]);

		$output['PROP_COUNTRY']=ucfirst($countryname);
		$output['PROP_TEL']=$propertyAddyArray[0];
		$output['PROP_FAX']=$propertyAddyArray[1];
		$output['PROP_EMAIL']=$propertyAddyArray[2];
		$output['PROP_URL']=$propertyAddyArray[3];
		
		$output['GATEWAY']=$plugin;
		/*
		$output['JR_GATEWAY_SENDDEPOSITTO']=jr_gettext('_JOMRES_CUSTOMTEXT_SENDDEPOSITTO'.$plugin,"Por favor para completar la reserva envÃ­e el importe de (Please to complete the booking send your deposit of) ");
		$output['JR_GATEWAY_BELOWADDRESS']=jr_gettext('_JOMRES_CUSTOMTEXT_BELOWADDRESS'.$plugin," al nÃºmero de cuenta IBAN/BIC: ES05 2100 0438 9601 0082 9924  SWIFT: CAIXESBBXXX");
		$output['JR_GATEWAY_CONTACTUS1']=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS1'.$plugin,"Si tiene algÃºn problema, por favor no dude en contactar con nosotros.(If you have any problems, please do not hesitate to contact us).");
		$output['JR_GATEWAY_CONTACTUS2']=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS2'.$plugin,"Envie un email a (email us at)  ");
		$output['_JOMRES_REVIEWS_SUBMIT'] = jr_gettext('_JOMRES_REVIEWS_SUBMIT',_JOMRES_REVIEWS_SUBMIT,false);
		*/
		$output['JR_GATEWAY_SENDDEPOSITTO']=jr_gettext('_JOMRES_CUSTOMTEXT_SENDDEPOSITTO'.$plugin,_JOMRES_CUSTOMTEXT_SENDDEPOSITTO,false,false);
		$output['JR_GATEWAY_BELOWADDRESS']=jr_gettext('_JOMRES_CUSTOMTEXT_BELOWADDRESS'.$plugin,_JOMRES_CUSTOMTEXT_BELOWADDRESS,false,false);
		$output['JR_GATEWAY_CONTACTUS1']=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS1'.$plugin,_JOMRES_CUSTOMTEXT_CONTACTUS1,false,false);
		$output['JR_GATEWAY_CONTACTUS2']=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS2'.$plugin,_JOMRES_CUSTOMTEXT_CONTACTUS2,false,false);
		$output['_JOMRES_REVIEWS_SUBMIT'] = jr_gettext('_JOMRES_CUSTOMTEXT_SUBMIT'.$plugin,_JOMRES_REVIEWS_SUBMIT,false);		
		

		
		$pageoutput[]=$output;
		$tmpl = new patTemplate();
		$tmpl->setRoot( get_showtime('ePointFilepath') );
		$tmpl->readTemplatesFromInput( 'j00600'.$plugin.'.html' );
		$tmpl->addRows( 'interrupt_outgoing', $pageoutput );
		$tmpl->displayParsedTemplate();
		}
		
	function touch_template_language()
		{
		$output=array();
		$plugin="cheque";

		$output[]		=jr_gettext('_JOMRES_CUSTOMTEXT_SENDDEPOSITTO'.$plugin,"Please send your deposit of ");
		$output[]		=jr_gettext('_JOMRES_CUSTOMTEXT_BELOWADDRESS'.$plugin," to the address IBAN/BIC: ES05 2100 0438 9601 0082 9924  SWIFT: CAIXESBBXXX");
		$output[]		=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS1'.$plugin,"Si tiene algún problema, por favor no dude en contactar con nosotros.(If you have any problems, please do not hesitate to contact us).");
		$output[]		=jr_gettext('_JOMRES_CUSTOMTEXT_CONTACTUS2'.$plugin,"Envie un email a (email us at) ");
		
		foreach ($output as $o)
			{
			echo $o;
			echo "<br/>";
			}
		}
	// This must be included in every Event/Mini-component
	function getRetVals()
		{
		return null;
		}		
	}
	
?>
