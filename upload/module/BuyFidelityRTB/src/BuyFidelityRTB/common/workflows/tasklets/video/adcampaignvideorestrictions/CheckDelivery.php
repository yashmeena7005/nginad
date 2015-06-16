<?php
/**
 * CDNPAL NGINAD Project
 *
 * @link http://www.nginad.com
 * @copyright Copyright (c) 2013-2015 CDNPAL Ltd. All Rights Reserved
 * @license GPLv3
 */

namespace buyrtbfidelity\workflows\tasklets\video\adcampaignvideorestrictions;

class CheckDelivery {
	
	public static function execute(&$Logger, &$Workflow, \model\openrtb\RtbBidRequest &$RtbBidRequest, \model\openrtb\RtbBidRequestImp &$RtbBidRequestImp, &$InsertionOrderLineItem, &$InsertionOrderLineItemVideoRestrictions) {
		
		$RtbBidRequestVideo = $RtbBidRequestImp->RtbBidRequestVideo;
		
		$result = true;
		
		if (empty($InsertionOrderLineItemVideoRestrictions->DeliveryCommaSeparated)):
			return $result;
		endif;
		
		$delivery_code_list = explode(',', $InsertionOrderLineItemVideoRestrictions->DeliveryCommaSeparated);

		if (!count($delivery_code_list)):
			return $result;
		endif;
		
		// Validate that the value is an array
		if (!is_array($RtbBidRequestVideo->delivery)):
			if ($Logger->setting_log === true):
			$Logger->log[] = "Param Not Required: No Values to Match: " . "Check video delivery code :: EXPECTED: "
					. 'Array(),'
					. " GOT: " . $RtbBidRequestVideo->delivery;
			endif;
			return $result;
		endif;
		
		/*
		 * All codes in the publisher ad zone
		 * for the publisher's video player settings
		 * have to be included in the VAST video demand
		 */
		foreach($RtbBidRequestVideo->delivery as $delivery_code_to_match):
		
			if (!in_array($delivery_code_to_match, $delivery_code_list)):
				
				$result = false;
				break;
			
			endif;
			
		endforeach;
		
		if ($result === false && $Logger->setting_log === true):
			$Logger->log[] = "Failed: " . "Check video delivery code :: EXPECTED: "
				. $InsertionOrderLineItemVideoRestrictions->DeliveryCommaSeparated
				. " GOT: " . join(',', $RtbBidRequestVideo->delivery);
		endif;
		
		return $result;
	}
}
