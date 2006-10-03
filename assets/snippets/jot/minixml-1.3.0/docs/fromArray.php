<?php

header("Content-type: text/plain\r\n\r\n");

require_once('./minixml.inc.php');

$xmlDoc = new MiniXMLDoc();

$xmlStructure = array(
						// Create a <spies> element to hold our team
					   "spies" => array(
					   			
								// Make a list of SPY people
								"spy"	=> array(
												array(
														'id'	=> '007',
														'type'	=> 'SuperSpy',
														'name'	=> 'James Bond',
														'email'	=> 'mi5@london.uk',
														'address'	=> 'Wherever he is needed most',
													),

												array(
														'id'	=> '6',
														'type'	=> 'RetiredSpy',
														'name'	=> 'Number 6',
														'email'	=> array(
																			'type'	=> 'private',
																			'-content' => 'mi6@london.uk',
																			'location'	=> 'office'),
														'address'	=> '123 Island Prison Lane',
													),
												array(
														'name'	=> 'Inspector Gadget',
														'id'	=> '13',
														'type'	=> 'NotReallyASpy',
														'email'	=> 'lost@aol.com',
														'friends' => array(

															'friend' => array(
																			// Gadget's first  friend
																			array(
																					'name' => array(
																									'first' => 'little',
																									'last'	=> 'girl'),
																					'age'	=> 12,

																					'hair'	=> array(
																										'color'	=> 'brown',
																										'length'=> 'long',
																									),
																				),

																			// Gadget's only other friend
																			array(
																					'name'	=> array(
																									'first'	=> 'smelly',
																									'last'	=> 'dog'),
																					'age'	=> 14,

																					'hair'	=> array(
																										'color'	=> 'dirtry blond',
																										'length'=> 'short',
																									),
																				),

																		) // end of list of Gadget's individual FRIENDs 

																) // end of the 'friends' element

													), // end description of SPY "Inspector Gadget"

											), // end list of individual spies

								), // end spies element
				);

										

$arrayOptions = array(
						'attributes'	=> array(
												'-all'	=> array('type', 'color'),
												'spy'	=> 'id',
												'email' => array('location'),
												'hair'	=> 'length',
											),
					);



$xmlDoc->fromArray($xmlStructure, $arrayOptions);

print "\n\n\nParsed ARRAY looks like this:\n";
var_dump($xmlDoc->toArray());

print "\n\nOUTPUT of fromArray() *with* OPTIONAL 'attributes' options set (for spy:id, email:location, hair:length, type, color)\n";

print $xmlDoc->toString();




?>
