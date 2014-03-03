var $j = jQuery.noConflict();
	// If we haven't yet got the function
	if 	(typeof(TagCompleter) != 'function') {
		function TagCompleter(tagEntryField, tagIndicatorList, delimiter) {

			var theEntry = $j('#'+tagEntryField);
			var theList = $j('#'+tagIndicatorList);

			// Make sure the elements that have been supplied exist
			if (!theEntry.length) {
				return;
			}

			// Attach events
			// Add hilights every time the tag field changes
			$j(theEntry).change(function(e) { addHilights(); });

			// Add tag every click on a tag in the list
			$j('#'+tagIndicatorList + ' li').click( function(e) { addTag(e); } );

			// Get an array of the current tags in the field
			var getTags = function() {
				// Get the contents of the field
				// Split is by commas
				// Trim each item of whitespace at the beginning and end
				var theTags = $j(theEntry).val().split(delimiter);
				$j.each(theTags, function(i,v) {
					theTags[i] = $j.trim(v);
						if (theTags[i] == '') {theTags.splice(i, 1); } // Remove any empty values
					});
				return theTags;
			};

			// Add the tag that has been clicked to the field
			var addTag = function (e) {
				var newTag = $j.trim($j(e.target).text());
				var oldTags = getTags();

				// Mark the document as dirty for Modx by triggering a "change" event
				$j(theEntry).trigger("change");

				// Is the tag already in the list? If so, remove it
				var thePos = $j.inArray(newTag, oldTags);
				var tagSpacer = (delimiter == ' ') ? '': ' ';
				if (thePos != -1) {
					oldTags.splice(thePos, 1);
					$j(theEntry).val(oldTags.join(delimiter+tagSpacer));
				} else { // Not in the list, so add it
					oldTags.push(newTag);
					$j(theEntry).val(oldTags.join(delimiter+tagSpacer));
				}
				addHilights();
			};

			// Highlight any tags in the tag list which are already in the field
			var addHilights = function() {

				var tagsInField = getTags();

				$j('#'+tagIndicatorList + ' li').each( function() {
					if ($j.inArray($j.trim($j(this).text()) , tagsInField) != -1) {
						$j(this).addClass('tagSelected');
					} else {
						$j(this).removeClass('tagSelected');
					}
				});

			};

			addHilights();

		}

	}