var mainMenu = {
	work: function() {
		modx.main.work()
	},
	reloadtree: function() {
		console.log('mainMenu.reloadtree()');
		setTimeout('modx.tree.restoreTree()', 50)
	},
	startrefresh: function(rFrame) {
		console.log('mainMenu.startrefresh(' + rFrame + ')');
		if(rFrame == 1) {
			setTimeout('modx.tree.restoreTree()', 50)
		}
		if(rFrame == 2) {
			setTimeout('modx.tree.restoreTree()', 50)
		}
		if(rFrame == 9 || rFrame == 10) {
			top.location.href = "../" + modx.MGR_DIR;
		}
	}
};

var tree = {
	ca: "open",
	document: document,
	saveFolderState: function() {
		console.log('tree.saveFolderState() off');
	},
	updateTree: function() {
		console.log('tree.updateTree()');
		modx.tree.updateTree()
	},
	restoreTree: function() {
		console.log('tree.restoreTree()');
		modx.tree.restoreTree()
	},
	reloadElementsInTree: function() {
		console.log('tree.reloadElementsInTree()');
		modx.tree.reloadElementsInTree()
	},
	resizeTree: function() {
		console.log('tree.resizeTree() off');
		// modx.tree.resizeTree()
	}
};

var setLastClickedElement = function(type, id) {
	modx.setLastClickedElement(type, id)
};

(function($, w, d, undefined) {
	$.extend(modx, {
		init: function() {
			if(!localStorage.getItem('MODX_lastPositionSideBar')) {
				localStorage.setItem('MODX_lastPositionSideBar', modx.config.tree_width);
			}
			setLastClickedElement(0, 0);
			modx.main.stopWork();
			modx.tree.init();
			modx.mainMenu.init();
			modx.resizer.init();
			modx.setLastClickedElement(0, 0);
			w.setInterval(modx.keepMeAlive, 1000 * 600); // Update session every 10min 1000 * 600
			$(w).on('load', function() {
				modx.updateMail(true); // First run update
			});
		},
		mainMenu: {
			id: 'mainMenu',
			init: function() {
				$('#' + modx.mainMenu.id).hover(function() {
					$('li', this).removeClass('close')
				});
				$('#' + modx.mainMenu.id + ' .nav li li a').click(function() {
					$('#' + modx.mainMenu.id + ' .nav>li').removeClass('active');
					$(this).closest('.nav>li').addClass('active close')
				});
				$('#' + modx.mainMenu.id + ' .nav > li > ul').css({
					'max-height': w.innerHeight - modx.config.menu_height + 'px'
				});
				modx.mainMenu.search.init();
			},
			navToggle: function(element) {
				$('#' + modx.mainMenu.id + ' .nav>li:not(:hover)').removeClass('active');
				element = $(element).closest('.nav>li');
				if(element.hasClass('active')) {
					element.removeClass('active')
				} else {
					element.addClass('active')
				}
			},
			search: {
				id: 'searchform',
				idResult: 'searchresult',
				classResult: 'ajaxSearchResults',
				searchResultWidth: '400',
				timer: 0,
				init: function() {
					modx.mainMenu.search.result = $('#' + modx.mainMenu.search.idResult);
					modx.mainMenu.search.result.css({
						'width': modx.mainMenu.search.searchResultWidth + 'px',
						'margin-right': -modx.mainMenu.search.searchResultWidth + 'px'
					});
					$('#' + modx.mainMenu.search.id + ' input').on('keyup', function(e) {
						var self = this;
						e.preventDefault();
						$(this).closest('form').find('.fa-refresh').remove();
						clearTimeout(modx.mainMenu.search.timer);

						if(this.value.length !== '' && this.value.length > 2) {
							modx.mainMenu.search.timer = setTimeout(function() {
								$.ajax({
									url: 'index.php?a=71&ajax=1',
									data: {
										searchid: self.value,
										submitok: 'Search'
									},
									method: 'post',
									beforeSend: function() {
										$(self).closest('form').append('<i class="fa fa-refresh fa-spin fa-fw"></i>')
									},
									dataFilter: function(data) {
										data = $(data).find('.' + modx.mainMenu.search.classResult);
										$('a', data).each(function(i, el) {
											$(el).attr('target', 'main').append('<i onclick="modx.openWindow({title:\'' + el.innerText + '\',id:\'' + el.id + '\',url:\'' + el.href + '\'});return false;">' + modx.style.icons_external_link + '</i>'
											);
										});
										return data.length ? data.html() : '';
									},
									success: function(data) {
										$(self).closest('form').find('.fa-refresh').fadeOut();
										if(data) {
											modx.mainMenu.search.result.html('<div class="' + modx.mainMenu.search.classResult + '">' + data + '</div>');
											$('a', modx.mainMenu.search.result).click(function() {
												$('.selected', modx.mainMenu.search.result).removeClass('selected');
												$(this).addClass('selected')
											});
											modx.mainMenu.search.open()
										} else {
											modx.mainMenu.search.empty()
										}
									},
									error: function(xhr, ajaxOptions, thrownError) {
										alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
									}
								})
							}, 300)
						} else {
							modx.mainMenu.search.empty()
						}
					}).on('focus click', function() {
						modx.mainMenu.search.open()
					}).on('blur', function() {
						modx.mainMenu.search.timer = setTimeout('modx.mainMenu.search.close()', 300)
					}).hover(function() {
						clearTimeout(modx.mainMenu.search.timer);
						modx.mainMenu.search.open()
					});
					$('#' + modx.mainMenu.search.id + ' .mask').hover(function() {
						clearTimeout(modx.mainMenu.search.timer);
						modx.mainMenu.search.open()
					}, function() {
						modx.mainMenu.search.timer = setTimeout('modx.mainMenu.search.close()', 300)
					});
					modx.mainMenu.search.result.hover(function() {
						modx.mainMenu.search.open()
					}, function() {
						modx.mainMenu.search.close()
					});
				},
				open: function() {
					if($('.' + modx.mainMenu.search.classResult, modx.mainMenu.search.result).length) {
						modx.mainMenu.search.result.addClass('open')
					}
				},
				close: function() {
					if(!modx.mainMenu.search.result.is(':hover')) {
						modx.mainMenu.search.result.removeClass('open')
					}
				},
				empty: function() {
					modx.mainMenu.search.result.removeClass('open').empty()
				}
			}
		},
		main: { // mainframe
			id: 'main',
			idFrame: 'mainframe',
			init: function() {
				modx.main.stopWork();
				modx.main.scrollWork();
			},
			work: function() {
				var elm = d.getElementById('workText');
				if(elm) elm.innerHTML = modx.style.icons_working + modx.lang.working;
				else setTimeout('modx.main.work()', 50);
			},
			stopWork: function() {
				var elm = d.getElementById('workText');
				if(elm) elm.innerHTML = "";
				else  setTimeout('modx.main.stopWork()', 50);
			},
			scrollWork: function() {
				var mainframe = d.getElementById(modx.main.idFrame).contentWindow;
				var currentPageY = localStorage.getItem('page_y');
				var pageUrl = localStorage.getItem('page_url');
				if(currentPageY === undefined) {
					localStorage.setItem('page_y', 0);
				}
				if(pageUrl === null) {
					pageUrl = mainframe.location.search.substring(1);
				}
				if(modx.main.getQueryVariable('a', pageUrl) == modx.main.getQueryVariable('a', mainframe.location.search.substring(1))) {
					if(modx.main.getQueryVariable('id', pageUrl) == modx.main.getQueryVariable('id', mainframe.location.search.substring(1))) {
						mainframe.scrollTo(0, currentPageY);
					}
				}
				mainframe.onscroll = function() {
					if(mainframe.pageYOffset > 0) {
						localStorage.setItem('page_y', mainframe.pageYOffset);
						localStorage.setItem('page_url', mainframe.location.search.substring(1));
					}
				}
			},
			getQueryVariable: function(variable, query) {
				var vars = query.split('&');
				for(var i = 0; i < vars.length; i++) {
					var pair = vars[i].split('=');
					if(decodeURIComponent(pair[0]) == variable) {
						return decodeURIComponent(pair[1]);
					}
				}
			}
		},
		resizer: { // resizer for tree / sidebar
			dragElement: null,
			oldZIndex: 9990,
			newZIndex: 9991,
			left: modx.config.tree_width,
			id: 'resizer',
			switcher: 'hideMenu',
			background: '#bbb',
			mask: null,
			init: function() {
				modx.resizer.mask = d.createElement('div');
				modx.resizer.mask.id = 'mask_resizer';
				modx.resizer.mask.style.zIndex = modx.resizer.oldZIndex;

				d.getElementById(modx.resizer.switcher).onclick = modx.resizer.toggle;
				d.getElementById(modx.resizer.id).onmousedown = modx.resizer.onMouseDown;
				d.getElementById(modx.resizer.id).onmouseup = modx.resizer.onMouseUp
			},
			onMouseDown: function(e) {
				if(e == null) e = w.event;
				modx.resizer.dragElement = e.target != null ? e.target : e.srcElement;
				if((e.buttons == 1 && w.event != null || e.button == 0) && modx.resizer.dragElement.id == modx.resizer.id) {
					modx.resizer.oldZIndex = modx.resizer.dragElement.style.zIndex;
					modx.resizer.dragElement.style.zIndex = modx.resizer.newZIndex;
					modx.resizer.dragElement.style.background = modx.resizer.background;
					localStorage.setItem('MODX_lastPositionSideBar', (modx.resizer.dragElement.offsetLeft > 0 ? modx.resizer.dragElement.offsetLeft : 0));
					d.body.appendChild(modx.resizer.mask);
					d.onmousemove = modx.resizer.onMouseMove;
					d.body.focus();
					d.onselectstart = function() {
						return false
					};
					modx.resizer.dragElement.ondragstart = function() {
						return false
					};
					return false
				}
			},
			onMouseMove: function(e) {
				if(e == null) var e = w.event;
				if(e.clientX > 0) {
					modx.resizer.left = e.clientX
				} else {
					modx.resizer.left = 0;
				}
				modx.resizer.dragElement.style.left = modx.resizer.left + 'px';
				d.getElementById('tree').style.width = modx.resizer.left + 'px';
				d.getElementById('main').style.left = modx.resizer.left + 'px'
				if(e.clientX < -2 || e.clientY < -2) {
					modx.resizer.onMouseUp(e);
				}
			},
			onMouseUp: function(e) {
				if(modx.resizer.dragElement != null && e.button == 0 && modx.resizer.dragElement.id == modx.resizer.id) {
					if(e.clientX > 0) {
						$('#frameset').removeClass('sidebar-closed').addClass('sidebar-opened');
						modx.resizer.left = e.clientX;
					} else {
						$('#frameset').removeClass('sidebar-opened').addClass('sidebar-closed');
						modx.resizer.left = 0;
					}
					d.cookie = 'MODX_positionSideBar=' + modx.resizer.left;
					modx.resizer.dragElement.style.zIndex = modx.resizer.oldZIndex;
					modx.resizer.dragElement.style.background = '';
					modx.resizer.dragElement.ondragstart = null;
					modx.resizer.dragElement = null;
					d.body.removeChild(modx.resizer.mask);
					d.onmousemove = null;
					d.onselectstart = null;
				}
			},
			toggle: function() {
				var pos = parseInt(d.getElementById('tree').offsetWidth) != 0 ? 0 : (localStorage.getItem('MODX_lastPositionSideBar') ? parseInt(localStorage.getItem('MODX_lastPositionSideBar')) : modx.config.tree_width);
				modx.resizer.setWidth(pos)
			},
			setWidth: function(pos) {
				if(pos > 0) {
					$('#frameset').removeClass('sidebar-closed').addClass('sidebar-opened');
					localStorage.setItem('MODX_lastPositionSideBar', 0);
				} else {
					$('#frameset').removeClass('sidebar-opened').addClass('sidebar-closed');
					localStorage.setItem('MODX_lastPositionSideBar', parseInt(d.getElementById('tree').offsetWidth));
				}
				d.cookie = 'MODX_positionSideBar=' + pos;
				d.getElementById('tree').style.width = pos + 'px';
				d.getElementById('resizer').style.left = pos + 'px';
				d.getElementById('main').style.left = pos + 'px';
			},
			setDefaultWidth: function() {
				modx.resizer.setWidth(modx.config.tree_width);
			}
		},
		tree: {
			rpcNode: null,
			itemToChange: null,
			selectedObjectName: null,
			selectedObject: 0,
			selectedObjectDeleted: 0,
			_rc: 0,
			init: function() {
				modx.tree.restoreTree()

			},
			toggleNode: function(node, indent, parent, expandAll, privatenode) {
				privatenode = (!privatenode || privatenode == '0') ? '0' : '1';
				modx.tree.rpcNode = node.parentNode.lastChild;

				var rpcNodeText, loadText = modx.lang.loading_doc_tree, signImg = d.getElementById("s" + parent),
					folderImg = d.getElementById("f" + parent);

				if(modx.tree.rpcNode.style.display != 'block') {
					// expand
					signImg.innerHTML = modx.style.tree_minusnode;
					folderImg.innerHTML = (privatenode == '0') ? modx.style.tree_folderopen : modx.style.tree_folderopen_secure;
					rpcNodeText = modx.tree.rpcNode.innerHTML;
					modx.openedArray[parent] = 1;

					if(rpcNodeText == "" || rpcNodeText.indexOf(loadText) > 0) {
						var i, spacer = '';
						//for(i = 0; i <= indent + 1; i++) spacer += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						var folderState = modx.tree.getFolderState();
						$("#buildText").html(modx.style.tree_info + loadText).show();
						modx.tree.rpcNode.innerHTML = "<span class='emptyNode' style='white-space:nowrap;'>" + spacer + loadText + "...<\/span>";
						$.get('index.php?a=1&f=nodes&indent=' + indent + '&parent=' + parent + '&expandAll=' + expandAll + folderState, function(data) {
							modx.tree.rpcLoadData(data)
						});
					}
					modx.tree.rpcNode.style.display = 'block';
					modx.tree.saveFolderState();
				}
				else {
					// collapse
					signImg.innerHTML = modx.style.tree_plusnode;
					folderImg.innerHTML = (privatenode == '0') ? modx.style.tree_folder : modx.style.tree_folder_secure;
					delete modx.openedArray[parent];
					modx.tree.rpcNode.style.display = 'none';
					modx.tree.rpcNode.innerHTML = '';
					modx.tree.saveFolderState();
				}
			},
			rpcLoadData: function(response) {
				if(modx.tree.rpcNode != null) {
					modx.tree.rpcNode.innerHTML = typeof response == 'object' ? response.responseText : response;
					modx.tree.rpcNode.style.display = 'block';
					modx.tree.rpcNode.loaded = true;
					$("#buildText").html('').fadeOut();
					if(localStorage.getItem('MODX_lastClickedElement')) {
						modx.tree.setActiveFromContextMenu(JSON.parse(localStorage.getItem('MODX_lastClickedElement'))[1]);
					}
					if(modx.tree.rpcNode.id == 'treeRoot') {
						if($('#binFull').length) modx.tree.showBinFull();
						else modx.tree.showBinEmpty();
					}
					if($('#mx_loginbox').length) {
						modx.tree.rpcNode.innerHTML = '';
						top.location = 'index.php';
					}
				}
			},
			treeAction: function(e, id, name, treedisp_children) {
				if(tree.ca == "move") {
					try {
						top.main.setMoveValue(id, name);
					} catch(oException) {
						alert(modx.lang.unable_set_parent);
					}
				}
				if(tree.ca == "open" || tree.ca == "") {
					if(id == 0) {
						top.main.location.href = "index.php?a=2";
					} else {
						var href = '';
						modx.setLastClickedElement(7, id);
						if(treedisp_children == 0) {
							href = "index.php?a=3&r=1&id=" + id + modx.tree.getFolderState();
						} else {
							href = "index.php?a=" + modx.config.tree_page_click + "&r=1&id=" + id;
						}
						if(e.shiftKey) {
							w.getSelection().removeAllRanges();
							modx.openWindow(href);
							modx.tree.reloadtree();
						} else {
							top.main.location.href = href;
						}
					}
				}
				if(tree.ca == "parent") {
					try {
						top.main.setParent(id, name);
					} catch(oException) {
						alert(modx.lang.unable_set_parent);
					}
				}
				if(tree.ca == "link") {
					try {
						top.main.setLink(id);
					} catch(oException) {
						alert(modx.lang.unable_set_link);
					}
				}
			},
			showPopup: function(id, title, pub, del, folder, e) {
				var x, y, mnu = d.getElementById('mx_contextmenu');

				if(modx.permission.publish_document == 1) {
					$('#item9').show();
					$('#item10').show();
					if(pub == 1) $('#item9').hide();
					else $('#item10').hide();
				} else {
					if($('#item5') != null) $('#item5').hide();
				}

				if(modx.permission.delete_document == 1) {
					$('#item4').show();
					$('#item8').show();
					if(del == 1) {
						$('#item4').hide();
						$('#item9').hide();
						$('#item10').hide();
					}
					else $('#item8').hide();
				}
				if(folder == 1) $('#item11').show();
				else $('#item11').hide();

				var bodyHeight = parseInt($('#tree').outerHeight());
				var bodyWidth = parseInt(d.body.offsetWidth);
				x = e.clientX > 0 ? e.clientX : e.pageX;
				if(x + mnu.offsetWidth > bodyWidth) {
					// make sure context menu is within frame
					x = Math.max(x - ((x + mnu.offsetWidth) - bodyWidth + 5), 0);
				}
				y = e.clientY > 0 ? e.clientY : e.pageY;
				y = modx.tree.getScrollY() + (y / 2);
				if(y + mnu.offsetHeight > bodyHeight) {
					// make sure context menu is within frame
					y = y - ((y + mnu.offsetHeight) - bodyHeight + 5);
				}
				modx.tree.itemToChange = id;
				modx.tree.selectedObjectName = title;
				modx.tree.dopopup(x + 5, y);
				e.cancelBubble = true;
				return false;
			},
			dopopup: function(x, y) {
				if(modx.tree.selectedObjectName.length > 20) {
					modx.tree.selectedObjectName = modx.tree.selectedObjectName.substr(0, 20) + "...";
				}
				var h, context = d.getElementById('mx_contextmenu'), elm = d.getElementById("nameHolder");
				context.style.left = x + (modx.config.textdir ? '-190' : '') + "px"; //offset menu to the left if rtl is selected
				context.style.top = y + "px";
				context.style.visibility = 'visible';
				elm.innerHTML = modx.tree.selectedObjectName;
				modx.tree._rc = 1;
				setTimeout(function() {
					modx.tree._rc = 0;
					top.main.onclick = function() {
						modx.tree.hideMenu(1)
					};
					d.onclick = function() {
						modx.tree.hideMenu(1)
					}
				}, 200);
			},
			getScrollY: function() {
				var scrOfY = 0;
				if(typeof(w.pageYOffset ) == 'number') {
					//Netscape compliant
					scrOfY = w.pageYOffset;
				} else if(d.body && (d.body.scrollLeft || d.body.scrollTop)) {
					//DOM compliant
					scrOfY = d.body.scrollTop;
				} else if(d.documentElement && d.documentElement.scrollTop) {
					//IE6 standards compliant mode
					scrOfY = d.documentElement.scrollTop;
				}
				return scrOfY;
			},
			menuHandler: function(action) {
				switch(action) {
					case 1 : // view
						modx.tree.setActiveFromContextMenu(modx.tree.itemToChange);
						top.main.document.location.href = "index.php?a=3&id=" + modx.tree.itemToChange;
						break;
					case 2 : // edit
						modx.setLastClickedElement(7, modx.tree.itemToChange);
						modx.tree.setActiveFromContextMenu(modx.tree.itemToChange);
						top.main.document.location.href = "index.php?a=27&id=" + modx.tree.itemToChange;
						break;
					case 3 : // new Resource
						top.main.document.location.href = "index.php?a=4&pid=" + modx.tree.itemToChange;
						break;
					case 4 : // delete
						if(modx.tree.selectedObjectDeleted) {
							alert("'" + modx.tree.selectedObjectName + "' " + modx.lang.already_deleted);
						} else {
							if(confirm("'" + modx.tree.selectedObjectName + "'\n\n" + modx.lang.confirm_delete_resource) === true) {
								top.main.document.location.href = "index.php?a=6&id=" + modx.tree.itemToChange;
							}
						}
						break;
					case 5 : // move
						top.main.document.location.href = "index.php?a=51&id=" + modx.tree.itemToChange;
						break;
					case 6 : // new Weblink
						top.main.document.location.href = "index.php?a=72&pid=" + modx.tree.itemToChange;
						break;
					case 7 : // duplicate
						if(confirm(modx.lang.confirm_resource_duplicate) == true) {
							top.main.document.location.href = "index.php?a=94&id=" + modx.tree.itemToChange;
						}
						break;
					case 8 : // undelete
						if(modx.tree.selectedObjectDeleted) {
							if(confirm("'" + modx.tree.selectedObjectName + "' " + modx.lang.confirm_undelete) === true) {
								top.main.document.location.href = "index.php?a=63&id=" + modx.tree.itemToChange;
							}
						} else {
							alert("'" + modx.tree.selectedObjectName + "'" + modx.lang.not_deleted);
						}
						break;
					case 9 : // publish
						if(confirm("'" + modx.tree.selectedObjectName + "' " + modx.lang.confirm_publish) === true) {
							top.main.document.location.href = "index.php?a=61&id=" + modx.tree.itemToChange;
						}
						break;
					case 10 : // unpublish
						if(modx.tree.itemToChange != modx.config.site_start) {
							if(confirm("'" + modx.tree.selectedObjectName + "' " + modx.lang.confirm_unpublish) === true) {
								top.main.document.location.href = "index.php?a=62&id=" + modx.tree.itemToChange;
							}
						} else {
							alert('Document is linked to site_start variable and cannot be unpublished!');
						}
						break;
					case 11 : // sort menu index
						top.main.document.location.href = "index.php?a=56&id=" + modx.tree.itemToChange;
						break;
					case 12 : // preview
						modx.openWindow({
							url: selectedObjectUrl,
							title: 'previeWin'
						}) //re-use 'new' window
						break;
					default :
						alert('Unknown operation command.');
				}
			},
			hideMenu: function() {
				if(modx.tree._rc) return false;
				d.getElementById('mx_contextmenu').style.visibility = 'hidden';
			},
			setHoverClass: function(el, dir) {
				if(dir) {
					el.classList.add('treeNodeHover')
				} else {
					el.classList.remove('treeNodeHover')
				}
			},
			setSelected: function(elSel) {
				$('.treeNodeSelected', d.getElementById('treeRoot')).removeClass('treeNodeSelected');
				elSel.classList.add('treeNodeSelected')

			},
			setActiveFromContextMenu: function(doc_id) {
				$('.treeNode').removeClass('treeNodeSelected');
				$('#node' + doc_id + '>.treeNode').addClass('treeNodeSelected')
			},
			restoreTree: function() {
				$("#buildText").html(modx.style.tree_info + modx.lang.loading_doc_tree).show();
				modx.tree.rpcNode = d.getElementById('treeRoot');
				$.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=2', function(data) {
					modx.tree.rpcLoadData(data)
				})
			},
			expandTree: function() {
				modx.tree.rpcNode = d.getElementById('treeRoot');
				$.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=1', function(data) {
					modx.tree.rpcLoadData(data)
				})
			},
			collapseTree: function() {
				modx.tree.rpcNode = d.getElementById('treeRoot');
				$.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=0', function(data) {
					modx.openedArray = [];
					modx.tree.saveFolderState();
					modx.tree.rpcLoadData(data);
				})
			},
			updateTree: function() {
				modx.tree.rpcNode = d.getElementById('treeRoot');
				var treeParams = 'a=1&f=nodes&indent=1&parent=0&expandAll=2&dt=' + d.sortFrm.dt.value + '&tree_sortby=' + d.sortFrm.sortby.value + '&tree_sortdir=' + d.sortFrm.sortdir.value + '&tree_nodename=' + d.sortFrm.nodename.value;
				$.get('index.php?' + treeParams, function(data) {
					modx.tree.rpcLoadData(data)
				})
			},
			getFolderState: function() {
				var oarray;
				if(modx.openedArray != [0]) {
					oarray = "&opened=";
					for(key in modx.openedArray) {
						if(modx.openedArray[key]) {
							oarray += key + "|";
						}
					}
				} else {
					oarray = "&opened=";
				}
				return oarray;
			},
			saveFolderState: function() {
				console.log('modx.tree.saveFolderState()');
				$.get('index.php?a=1&f=nodes&savestateonly=1' + modx.tree.getFolderState())
			},
			showSorter: function() {
				$('#floater').toggleClass('show')
			},
			emptyTrash: function() {
				if(confirm(modx.lang.confirm_empty_trash) == true) {
					top.main.document.location.href = "index.php?a=64";
				}
			},
			showBinFull: function() {
				if($('#Button10').length) {
					$('#Button10').attr('title', modx.lang.empty_recycle_bin)
						.addClass('treeButton')
						.removeClass('treeButtonDisabled')
						.html(modx.style.empty_recycle_bin)
						.click(function() {
							modx.tree.emptyTrash()
						})
				}
			},
			showBinEmpty: function() {
				if($('#Button10').length) {
					$('#Button10').attr('title', modx.lang.empty_recycle_bin_empty)
						.addClass('treeButton')
						.html(modx.style.empty_recycle_bin_empty)
						.off('click')
				}
			},
			unlockElement: function(type, id, domEl) {
				var msg = modx.lockedElementsTranslation.msg.replace('[+id+]', id).replace('[+element_type+]', modx.lockedElementsTranslation['type' + type]);
				if(confirm(msg) == true) {
					$.get('index.php?a=67&type=' + type + '&id=' + id, function(data) {
						if(data == 1) {
							$(domEl).fadeOut();
						}
						else alert(data);
					});
				}
			},
			resizeTree: function() {
			},
			reloadElementsInTree: function() {
				$.ajax({
					url: 'index.php?a=1&f=tree',
					dataFilter: function(data) {
						var d = [];
						d['tabDoc'] = $(data).find('#tabDoc > div').html();
						d['tabTemp'] = $(data).find('#tabTemp > .panel-group').html();
						d['tabTV'] = $(data).find('#tabTV > .panel-group').html();
						d['tabCH'] = $(data).find('#tabCH > .panel-group').html();
						d['tabSN'] = $(data).find('#tabSN > .panel-group').html();
						d['tabPL'] = $(data).find('#tabPL > .panel-group').html();
						d['tabMD'] = $(data).find('#tabMD > .panel-group').html();
						return d;
					},
					success: function(data) {
						$('#tabDoc > div').html(data['tabDoc']);
						modx.tree.init();

						// init ElementsInTree
						savePositions();

						$('#tabTemp > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabTemp'] + '</div>');
						$('#tabTV > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabTV'] + '</div>');
						$('#tabCH > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabCH'] + '</div>');
						$('#tabSN > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabSN'] + '</div>');
						$('#tabPL > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabPL'] + '</div>');
						$('#tabMD > .panel-group').before('<div class="panel-group clone" style="display: none">' + data['tabMD'] + '</div>');

						setRememberCollapsedCategories();

						$('#treeHolder .tab-page .panel-group:not(.clone)').remove();
						$('#treeHolder .tab-page .panel-group.clone').show().removeClass('clone');

						loadPositions();

						initQuicksearch('tree_site_templates_search', 'tree_site_templates');
						$('#tree_site_templates_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_templates .accordion-toggle').removeClass("collapsed");
							$('#tree_site_templates .accordion-toggle').addClass("no-events");
							$('.site_templates').collapse('show');
						}).on('blur', function() {
							setRememberCollapsedCategories(searchFieldCache);
							$('#tree_site_templates .accordion-toggle').removeClass("no-events");
						});

						initQuicksearch('tree_site_tmplvars_search', 'tree_site_tmplvars');
						$('#tree_site_tmplvars_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_tmplvars .accordion-toggle').removeClass("collapsed");
							$('#tree_site_tmplvars .accordion-toggle').addClass("no-events");
							$('.site_tmplvars').collapse('show');
						}).on('blur', function() {
							setRememberCollapsedCategories(searchFieldCache);
							$('#tree_site_tmplvars .accordion-toggle').removeClass("no-events");
						});

						initQuicksearch('tree_site_htmlsnippets_search', 'tree_site_htmlsnippets');
						$('#tree_site_htmlsnippets_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_htmlsnippets .accordion-toggle').removeClass("collapsed");
							$('#tree_site_htmlsnippets .accordion-toggle').addClass("no-events");
							$('.site_htmlsnippets').collapse('show');
						}).on('blur', function() {
							setRememberCollapsedCategories(searchFieldCache);
							$('#tree_site_htmlsnippets .accordion-toggle').removeClass("no-events");
						});

						initQuicksearch('tree_site_snippets_search', 'tree_site_snippets');
						$('#tree_site_snippets_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_snippets .accordion-toggle').removeClass("collapsed");
							$('#tree_site_snippets .accordion-toggle').addClass("no-events");
							$('.site_snippets').collapse('show');
						}).on('blur', function() {
							setRememberCollapsedCategories(searchFieldCache);
							$('#tree_site_snippets .accordion-toggle').removeClass("no-events");
						});

						initQuicksearch('tree_site_plugins_search', 'tree_site_plugins');
						$('#tree_site_plugins_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_plugins .accordion-toggle').removeClass("collapsed");
							$('#tree_site_plugins .accordion-toggle').addClass("no-events");
							$('.site_plugins').collapse('show');
						}).on('blur', function() {
							setRememberCollapsedCategories(searchFieldCache);
							$('#tree_site_plugins .accordion-toggle').removeClass("no-events");
						});

						initQuicksearch('tree_site_modules_search', 'tree_site_modules');
						$('#tree_site_modules_search').on('focus', function() {
							searchFieldCache = elementsInTreeParams.cat_collapsed;
							$('#tree_site_modules .accordion-toggle').addClass('no-events');
							$('#tree_site_modules .accordion-toggle').removeClass('collapsed');
							$('.site_modules').collapse('show');
						}).on('blur', function() {
							$('#tree_site_modules .accordion-toggle').removeClass('no-events');
							setRememberCollapsedCategories(searchFieldCache);
						});

						// Shift-Mouseclick opens/collapsed all categories
						$(".accordion-toggle").click(function(e) {
							e.preventDefault();
							var thisItemCollapsed = $(this).hasClass("collapsed");
							if(e.shiftKey) {
								// Shift-key pressed
								var toggleItems = $(this).closest(".panel-group").find("> .panel .accordion-toggle");
								var collapseItems = $(this).closest(".panel-group").find("> .panel > .panel-collapse");
								if(thisItemCollapsed) {
									toggleItems.removeClass("collapsed");
									collapseItems.collapse("show");
								} else {
									toggleItems.addClass("collapsed");
									collapseItems.collapse("hide");
								}
								// Save states to localStorage
								toggleItems.each(function() {
									state = $(this).hasClass("collapsed") ? 1 : 0;
									setLastCollapsedCategory($(this).data("cattype"), $(this).data("catid"), state);
								});
								writeElementsInTreeParamsToStorage();
							} else {
								$(this).toggleClass("collapsed");
								$($(this).attr("href")).collapse("toggle");
								// Save state to localStorage
								state = thisItemCollapsed ? 0 : 1;
								setLastCollapsedCategory($(this).data("cattype"), $(this).data("catid"), state);
								writeElementsInTreeParamsToStorage();
							}
						});

						// end ElementsInTree
					}
				})
			}
		},
		setLastClickedElement: function(type, id) {
			localStorage.setItem('MODX_lastClickedElement', '[' + type + ',' + id + ']');
		},
		removeLocks: function() {
			if(confirm(modx.lang.confirm_remove_locks) === true) {
				top.main.document.location.href = "index.php?a=67";
			}
		},
		openCredits: function() {
			top.main.document.location.href = "index.php?a=18";
			setTimeout('modx.main.stopWork()', 2000);
		},
		keepMeAlive: function() {
			$.getJSON('includes/session_keepalive.php?tok=' + d.getElementById('sessTokenInput').value + '&o=' + Math.random(), function(data) {
				if(data.status != 'ok') {
					w.location.href = 'index.php?a=8';
				}

			})
		},
		updateMail: function(now) {
			try {
				if(now) {
					$.post('index.php', {updateMsgCount: true}, function(data) {
						var counts = data.split(',');
						var elm = d.getElementById('msgCounter');
						if(elm) {
							elm.innerHTML = counts[1];
							elm.style.display = counts[1] > 0 ? 'block' : 'none'
						}
						elm = d.getElementById('newMail');
						if(elm) {
							elm.innerHTML = '<a href="index.php?a=10" target="main">' + modx.lang.inbox + '(' + counts[0] + ' / ' + counts[1] + ')</a>';
							elm.style.display = counts[0] > 0 ? 'block' : 'none'
						}
					})
				}
				return false;
			} catch(oException) {
				setTimeout('modx.updateMail(true)', 1000 * 60); // 1000 * 60
			}
		},
		openWindow: function(data) {
			if(typeof data != 'object') {
				data = {
					"url": data
				}
			}
			if(data.width == undefined)
				data.width = parseInt(w.innerWidth * 0.9) + 'px';
			if(data.height == undefined)
				data.height = parseInt(w.innerHeight * 0.8) + 'px';
			if(data.left == undefined)
				data.left = parseInt(w.innerWidth * 0.05) + 'px';
			if(data.top == undefined)
				data.top = parseInt(w.innerHeight * 0.1) + 'px';
			if(data.title == undefined)
				data.title = Math.floor((Math.random() * 999999) + 1);
			if(data.url !== undefined) {
				if(modx.plugins.EVOmodal == 1) { // used EVO.modal
					top.EVO.modal.show(data)
				} else {
					w.open(data.url, data.title, 'width=' + data.width + ',height=' + data.height + ',top=' + data.top + ',left=' + data.left + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no');
				}
			}
			return false;
		},
		getWindowDimension: function() {
			var width = 0;
			var height = 0;

			if(typeof( window.innerWidth ) == 'number') {
				width = window.innerWidth;
				height = window.innerHeight;
			} else if(document.documentElement &&
				( document.documentElement.clientWidth ||
				document.documentElement.clientHeight )) {
				width = document.documentElement.clientWidth;
				height = document.documentElement.clientHeight;
			}
			else if(document.body &&
				( document.body.clientWidth || document.body.clientHeight )) {
				width = document.body.clientWidth;
				height = document.body.clientHeight;
			}

			return {'width': width, 'height': height};
		}
	});

	$(d).ready(function() {
		modx.init()
	})

})(jQuery, window, document, undefined);