(function($, w, d, m, u) {
	'use strict';
	var _ = {
		frameset: 'frameset',
		init: function() {
			if(!localStorage.getItem('MODX_lastPositionSideBar')) {
				localStorage.setItem('MODX_lastPositionSideBar', this.config.tree_width)
			}
			this.tree.init();
			this.mainMenu.init();
			this.resizer.init();
			this.search.init();
			this.setLastClickedElement(0, 0);
			w.setInterval(this.keepMeAlive, 1000 * 60 * this.config.session_timeout);
			w.onload = this.updateMail(true);
			d.onclick = this.hideDropDown
		},
		mainMenu: {
			id: 'mainMenu',
			init: function() {
				//console.log('modx.mainMenu.init()');
				var els, i, ii;
				d.getElementById(this.id).onclick = function(e) {
					var t = e.target.closest('a');
					if(t !== null) {
						if(t.classList.contains('dropdown-toggle')) {
							modx.hideDropDown(e);
							this.classList.add('show');
							e.stopPropagation()
						}
						if(!e.defaultPrevented) {
							els = this.querySelectorAll('.nav > li.active');
							for(i = 0; i < els.length; i++) els[i].classList.remove('active');
							this.classList.remove('show');
							if(t.parentNode.classList.contains('dropdown-menu')) {
								t.parentNode.parentNode.classList.add('active')
							} else {
								t.parentNode.classList.add('active');
								if(t.parentNode.parentNode.classList.contains('dropdown-menu')) {
									els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav li.selected');
									for(i = 0; i < els.length; i++) els[i].classList.remove('selected');
									t.parentNode.classList.add('selected', 'hover');
									if(t.parentNode.parentNode.parentNode.classList.contains('dropdown'))
										t.parentNode.parentNode.parentNode.classList.add('active');
									if(t.parentNode.parentNode.id)
										d.getElementById(t.parentNode.parentNode.id.replace('parent_', '')).classList.add('selected')
								}
							}
						}
					}
				};
				els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav > li');
				for(i = 0; i < els.length; i++) {
					els[i].onmouseover = function() {
						els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav > li.hover');
						for(ii = 0; ii < els.length; ii++) els[ii].classList.remove('hover');
						this.classList.add('hover')
					}
				}
				els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav > li li');
				for(i = 0; i < els.length; i++) {
					els[i].onmouseover = function() {
						els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav > li li');
						for(ii = 0; ii < els.length; ii++) {
							els[ii].classList.remove('hover')
						}
						this.classList.add('hover');
						els = d.querySelectorAll('#' + modx.mainMenu.id + ' .nav .sub-menu');
						for(ii = 0; ii < els.length; ii++) {
							if(els[ii].id !== 'parent_' + this.id)
								els[ii].classList.remove('show')
						}
						this.parentNode.classList.add('hover')
					}
				}
			},
			openSubMenu: function(e) {
				var parent = e.target.parentNode;
				if(parent.parentNode.classList.contains('dropdown-menu')) {
					var a = e.target;
					var href = a.href.split('?')[1];
					// e.preventDefault();
					// e.stopPropagation();
					var sub = parent.parentNode.parentNode.children['parent_' + parent.id];
					if(sub) {
						sub.classList.add('show')
					} else {
						modx.post(modx.MODX_SITE_URL + modx.MGR_DIR + '/media/style/' + modx.config.theme + '/ajax.php', href + '&parent=' + parent.id, function(r) {
							if(r) {
								var ul = d.createElement('ul');
								parent.parentNode.parentNode.appendChild(ul);
								ul.innerHTML = r;
								ul.style.left = parent.parentNode.offsetWidth + 'px';
								ul.id = 'parent_' + parent.id;
								ul.className = 'sub-menu dropdown-menu show';
								var els = ul.querySelectorAll('li');
								for(var ii = 0; ii < els.length; ii++) {
									els[ii].onmouseover = function() {
										for(var iii = 0; iii < els.length; iii++) els[iii].classList.remove('hover');
										this.classList.add('hover')
									}
								}
							}
						})
					}
				}
			}
		},
		search: {
			id: 'searchform',
			idResult: 'searchresult',
			idInput: 'searchid',
			classResult: 'ajaxSearchResults',
			classMask: 'mask',
			timer: 0,
			init: function() {
				this.result = d.getElementById(this.idResult);
				var t = this,
					el = d.getElementById(this.idInput),
					r = d.createElement('i');
				r.className = 'fa fa-refresh fa-spin fa-fw';
				el.parentNode.appendChild(r);
				el.onkeyup = function(e) {
					e.preventDefault();
					clearTimeout(t.timer);
					if(el.value.length !== '' && el.value.length > 2) {
						t.timer = setTimeout(function() {
							var xhr = modx.XHR();
							xhr.open('GET', 'index.php?a=71&ajax=1&submitok=Search&searchid=' + el.value, true);
							xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
							xhr.onload = function() {
								if(this.status === 200) {
									r.style.display = 'none';
									var div = d.createElement('div');
									div.innerHTML = this.responseText;
									var o = div.getElementsByClassName(t.classResult)[0];
									if(o) {
										if(o.innerHTML !== '') {
											t.result.innerHTML = o.outerHTML;
											t.open();
											t.result.onclick = function(e) {
												if(e.target.tagName === 'I') {
													modx.openWindow({
														title: e.target.parentNode.innerText,
														id: e.target.parentNode.id,
														url: e.target.parentNode.href
													});
													return false
												}
												var p = (e.target.tagName === 'A' && e.target) || e.target.parentNode;
												if(p.tagName === 'A') {
													var el = t.result.querySelector('.selected');
													if(el) el.className = '';
													p.className = 'selected'
												}
											}
										} else {
											t.empty()
										}
									} else {
										t.empty()
									}
								}
							};
							xhr.onloadstart = function() {
								r.style.display = 'block'
							};
							xhr.onerror = function() {
								console.warn(this.status)
							};
							xhr.send()
						}, 300)
					} else {
						t.empty()
					}
				};
				el.onfocus = function() {
					t.open()
				};
				el.onclick = function() {
					t.open()
				};
				el.onblur = function() {
					t.close()
				};
				el.onmouseover = function() {
					t.open()
				};
				d.getElementById(this.id).getElementsByClassName(this.classMask)[0].onmouseover = function() {
					t.open()
				};
				d.getElementById(this.id).getElementsByClassName(this.classMask)[0].onmouseout = function() {
					t.close()
				}
			},
			open: function() {
				if(this.result.getElementsByClassName(this.classResult)[0]) {
					this.result.classList.add('open')
				}
			},
			close: function() {
				this.result.classList.remove('open')
			},
			empty: function() {
				this.result.classList.remove('open');
				this.result.innerHTML = ''
			}
		},
		main: {
			id: 'main',
			idFrame: 'mainframe',
			as: null,
			init: function() {
				this.stopWork();
				this.scrollWork();
				m.onclick = modx.hideDropDown
			},
			work: function() {
				var el = d.getElementById('workText');
				if(el) {
					el.innerHTML = modx.style.icons_working + modx.lang.working;
					el.style.display = 'block';
				} else setTimeout('modx.main.work()', 50)
			},
			stopWork: function() {
				modx.tree.setSelectedByContext();
				var el = d.getElementById('workText');
				if(el) {
					el.style.display = 'none';
				} else setTimeout('modx.main.stopWork()', 50)
			},
			scrollWork: function() {
				var a = d.getElementById(modx.main.idFrame).contentWindow,
					b = localStorage.getItem('page_y'),
					c = localStorage.getItem('page_url');
				if(b === u) {
					localStorage.setItem('page_y', 0)
				}
				if(c === null) {
					c = a.location.search.substring(1)
				}
				if((modx.main.getQueryVariable('a', c) === modx.main.getQueryVariable('a', a.location.search.substring(1))) && (modx.main.getQueryVariable('id', c) === modx.main.getQueryVariable('id', a.location.search.substring(1)))) {
					a.scrollTo(0, b)
				}
				a.onscroll = function() {
					if(a.pageYOffset > 0) {
						localStorage.setItem('page_y', a.pageYOffset);
						localStorage.setItem('page_url', a.location.search.substring(1))
					}
				}
			},
			getQueryVariable: function(v, q) {
				var vars = q.split('&');
				for(var i = 0; i < vars.length; i++) {
					var p = vars[i].split('=');
					if(decodeURIComponent(p[0]) === v) {
						return decodeURIComponent(p[1])
					}
				}
			}
		},
		resizer: {
			dragElement: null,
			oldZIndex: 99,
			newZIndex: 999,
			left: modx.config.tree_width,
			id: 'resizer',
			switcher: 'hideMenu',
			background: 'rgba(0, 0, 0, 0.1)',
			mask: null,
			init: function() {
				modx.resizer.mask = d.createElement('div');
				modx.resizer.mask.id = 'mask_resizer';
				modx.resizer.mask.style.zIndex = modx.resizer.oldZIndex;
				d.getElementById(modx.resizer.switcher).onclick = modx.resizer.toggle;
				d.getElementById(modx.resizer.id).onmousedown = modx.resizer.onMouseDown;
				d.getElementById(modx.resizer.id).onmouseup = modx.resizer.mask.onmouseup = modx.resizer.onMouseUp
			},
			onMouseDown: function(e) {
				e = e || w.event;
				modx.resizer.dragElement = e.target !== null ? e.target : e.srcElement;
				if((e.buttons === 1 || e.button === 0) && modx.resizer.dragElement.id === modx.resizer.id) {
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
				e = e || w.event;
				if(e.clientX > 0) {
					modx.resizer.left = e.clientX
				} else {
					modx.resizer.left = 0
				}
				modx.resizer.dragElement.style.left = modx.resizer.left + 'px';
				d.getElementById('tree').style.width = modx.resizer.left + 'px';
				d.getElementById('main').style.left = modx.resizer.left + 'px';
				if(e.clientX < -2 || e.clientY < -2) {
					modx.resizer.onMouseUp(e)
				}
			},
			onMouseUp: function(e) {
				if(modx.resizer.dragElement !== null && e.button === 0 && modx.resizer.dragElement.id === modx.resizer.id) {
					if(e.clientX > 0) {
						d.body.classList.add('sidebar-opened');
						d.body.classList.remove('sidebar-closed');
						modx.resizer.left = e.clientX
					} else {
						d.body.classList.remove('sidebar-opened');
						d.body.classList.add('sidebar-closed');
						modx.resizer.left = 0
					}
					d.cookie = 'MODX_positionSideBar=' + modx.resizer.left;
					modx.resizer.dragElement.style.zIndex = modx.resizer.oldZIndex;
					modx.resizer.dragElement.style.background = '';
					modx.resizer.dragElement.ondragstart = null;
					modx.resizer.dragElement = null;
					d.body.removeChild(modx.resizer.mask);
					d.onmousemove = null;
					d.onselectstart = null
				}
			},
			toggle: function() {
				var p = parseInt(d.getElementById('tree').offsetWidth) !== 0 ? 0 : (localStorage.getItem('MODX_lastPositionSideBar') ? parseInt(localStorage.getItem('MODX_lastPositionSideBar')) : modx.config.tree_width);
				modx.resizer.setWidth(p)
			},
			setWidth: function(a) {
				if(a > 0) {
					d.body.classList.add('sidebar-opened');
					d.body.classList.remove('sidebar-closed');
					localStorage.setItem('MODX_lastPositionSideBar', 0)
				} else {
					d.body.classList.remove('sidebar-opened');
					d.body.classList.add('sidebar-closed');
					localStorage.setItem('MODX_lastPositionSideBar', parseInt(d.getElementById('tree').offsetWidth))
				}
				d.cookie = 'MODX_positionSideBar=' + a;
				d.getElementById('tree').style.width = a + 'px';
				d.getElementById('resizer').style.left = a + 'px';
				d.getElementById('main').style.left = a + 'px'
			},
			setDefaultWidth: function() {
				modx.resizer.setWidth(modx.config.tree_width)
			}
		},
		tree: {
			ctx: null,
			rpcNode: null,
			itemToChange: null,
			selectedObjectName: null,
			selectedObject: 0,
			selectedObjectDeleted: 0,
			selectedObjectUrl: '',
			init: function() {
				this.restoreTree()
			},
			toggleNode: function(e, a, b, c) {
				e = e || w.event;
				if(e.ctrlKey) return;
				e.stopPropagation();
				this.rpcNode = d.getElementById('node' + b).lastChild;
				var rpcNodeText,
					loadText = modx.lang.loading_doc_tree,
					iconNodeToggle = d.getElementById("s" + b),
					iconNode = d.getElementById("f" + b);
				if(this.rpcNode.innerHTML === '') {
					iconNodeToggle.innerHTML = iconNodeToggle.dataset.iconCollapsed;
					iconNode.innerHTML = iconNode.dataset.iconFolderOpen;
					rpcNodeText = this.rpcNode.innerHTML;
					modx.openedArray[b] = 1;
					if(rpcNodeText === "" || rpcNodeText.indexOf(loadText) > 0) {
						var folderState = this.getFolderState();
						var el = d.getElementById('buildText');
						if(el) {
							el.innerHTML = modx.style.tree_info + loadText;
							el.style.display = 'block'
						}
						modx.get('index.php?a=1&f=nodes&indent=' + a + '&parent=' + b + '&expandAll=' + c + folderState, function(r) {
							modx.tree.rpcLoadData(r)
						})
					}
					this.saveFolderState()
				} else {
					iconNodeToggle.innerHTML = iconNodeToggle.dataset.iconExpanded;
					iconNode.innerHTML = iconNode.dataset.iconFolderClose;
					delete modx.openedArray[b];
					modx.animation.slideUp(this.rpcNode, 80, function() {
						this.innerHTML = '';
					});
					this.saveFolderState()
				}
			},
			rpcLoadData: function(a) {
				if(this.rpcNode !== null) {
					this.rpcNode.innerHTML = typeof a === 'object' ? a.responseText : a;
					this.rpcNode.loaded = true;
					if(this.rpcNode.id !== 'treeRoot') {
						modx.animation.slideDown(this.rpcNode, 80);
					}
					var el = d.getElementById('buildText');
					if(el) {
						el.style.display = 'none'
					}
					if(this.rpcNode.id === 'treeRoot') {
						el = d.getElementById('binFull');
						if(el) this.showBin(true);
						else this.showBin(false)
					}
					el = d.getElementById('mx_loginbox');
					if(el) {
						modx.animation.slideUp(this.rpcNode, 80, function() {
							this.innerHTML = '';
						});
						w.location = 'index.php'
					}
				}
			},
			treeAction: function(e, a, b, c, f, g) {
				if(e.ctrlKey) return;
				if(tree.ca === "move") {
					try {
						this.setSelectedByContext(a);
						m.setMoveValue(a, b)
					} catch(oException) {
						alert(modx.lang.unable_set_parent)
					}
				}
				if(tree.ca === "open" || tree.ca === "") {
					if(a === 0) {
						m.location.href = "index.php?a=2"
					} else {
						var href = '';
						modx.setLastClickedElement(7, a);
						if(!isNaN(parseFloat(c)) && isFinite(c)) {
							href = "index.php?a=" + c + "&r=1&id=" + a + (g === 0 ? this.getFolderState() : '')
						} else {
							href = c;
						}
						if(g === 2) {
							if(f !== 1) {
								href = '';
							}
							d.getElementById('s' + a).onclick(e)
						}
						if(href) {
							if(e.shiftKey) {
								w.getSelection().removeAllRanges();
								modx.openWindow(href);
								this.restoreTree()
							} else {
								m.location.href = href
							}
						}
					}
					var el = d.querySelector('#node' + a + '>.node');
					modx.tree.setSelected(el)
				}
				if(tree.ca === "parent") {
					try {
						this.setSelectedByContext(a);
						m.setParent(a, b)
					} catch(oException) {
						alert(modx.lang.unable_set_parent)
					}
				}
				if(tree.ca === "link") {
					try {
						this.setSelectedByContext(a);
						m.setLink(a)
					} catch(oException) {
						alert(modx.lang.unable_set_link)
					}
				}
			},
			showPopup: function(a, b, c, f, g, e) {
				if(e.ctrlKey) return;
				e.preventDefault();
				var node = d.getElementById('node' + a),
					tree = d.getElementById('tree');

				if(node.dataset.contextmenu) {
					e.target.dataset.toggle = '#contextmenu';
					//if(e.type === 'contextmenu') {
					modx.hideDropDown(e);
					//}
					this.ctx = d.createElement('div');
					this.ctx.id = 'contextmenu';
					this.ctx.className = 'dropdown-menu';
					d.getElementById(modx.frameset).appendChild(this.ctx);
					this.setSelectedByContext(a);
					var dataJson = JSON.parse(node.dataset.contextmenu);
					for(var key in dataJson) {
						if(dataJson.hasOwnProperty(key)) {
							var item = d.createElement('div');
							for(var k in dataJson[key]) {
								if(dataJson[key].hasOwnProperty(k)) {
									if(k.substring(0, 2) === 'on') {
										var onEvent = dataJson[key][k];
										item[k] = function() {
											eval(onEvent)
										}
									} else {
										item[k] = dataJson[key][k]
									}
								}
							}
							if(key.indexOf('header') === 0) item.className += ' menuHeader';
							if(key.indexOf('item') === 0) item.className += ' menuLink';
							if(key.indexOf('seperator') === 0 || key.indexOf('separator') === 0) item.className += ' seperator separator';
							this.ctx.appendChild(item)
						}
					}
					var bodyHeight = tree.offsetHeight + tree.offsetTop;
					var x = e.clientX > 0 ? e.clientX : e.pageX;
					var y = e.clientY > 0 ? e.clientY : e.pageY;
					if(y + this.ctx.offsetHeight / 2 > bodyHeight) {
						y = bodyHeight - this.ctx.offsetHeight - 5
					} else if(y - this.ctx.offsetHeight / 2 < tree.offsetTop) {
						y = tree.offsetTop + 5
					} else {
						y = y - this.ctx.offsetHeight / 2
					}
					var el = e.target.closest('.node');
					if(el === null) x += 50;
					this.itemToChange = a;
					this.selectedObjectName = b;
					this.dopopup(this.ctx, x + 10, y);
					this.ctx.classList.add('show')
				} else {
					var ctx = d.getElementById('mx_contextmenu');
					e.target.dataset.toggle = '#mx_contextmenu';
					//if(e.type === 'contextmenu') {
					modx.hideDropDown(e);
					//}
					this.setSelectedByContext(a);
					var i4 = d.getElementById('item4'),
						i5 = d.getElementById('item5'),
						i8 = d.getElementById('item8'),
						i9 = d.getElementById('item9'),
						i10 = d.getElementById('item10'),
						i11 = d.getElementById('item11');
					if(modx.permission.publish_document === 1) {
						i9.style.display = 'block';
						i10.style.display = 'block';
						if(c === 1) i9.style.display = 'none';
						else i10.style.display = 'none'
					} else {
						i5.style.display = 'none'
					}
					if(modx.permission.delete_document === 1) {
						i4.style.display = 'block';
						i8.style.display = 'block';
						if(f === 1) {
							i4.style.display = 'none';
							i9.style.display = 'none';
							i10.style.display = 'none'
						} else {
							i8.style.display = 'none'
						}
					}
					if(g === 1) i11.style.display = 'block';
					else i11.style.display = 'none';
					var bodyHeight = tree.offsetHeight + tree.offsetTop;
					var x = e.clientX > 0 ? e.clientX : e.pageX;
					var y = e.clientY > 0 ? e.clientY : e.pageY;
					if(y + ctx.offsetHeight / 2 > bodyHeight) {
						y = bodyHeight - ctx.offsetHeight - 5
					} else if(y - ctx.offsetHeight / 2 < tree.offsetTop) {
						y = tree.offsetTop + 5
					} else {
						y = y - ctx.offsetHeight / 2
					}
					var el = e.target.closest('.node');
					if(el === null) x += 50;
					this.itemToChange = a;
					this.selectedObjectName = b;
					this.dopopup(ctx, x + 10, y)
				}
				e.stopPropagation()
			},
			dopopup: function(el, a, b) {
				if(this.selectedObjectName.length > 20) {
					this.selectedObjectName = this.selectedObjectName.substr(0, 20) + "..."
				}
				var f = d.getElementById("nameHolder");
				el.style.left = a + (modx.config.textdir ? '-190' : '') + "px";
				el.style.top = b + "px";
				el.classList.add('show');
				f.innerHTML = this.selectedObjectName;
			},
			menuHandler: function(a) {
				switch(a) {
					case 1:
						this.setActiveFromContextMenu(this.itemToChange);
						m.location.href = "index.php?a=3&id=" + this.itemToChange;
						break;
					case 2:
						this.setActiveFromContextMenu(this.itemToChange);
						m.location.href = "index.php?a=27&r=1&id=" + this.itemToChange;
						break;
					case 3:
						m.location.href = "index.php?a=4&pid=" + this.itemToChange;
						break;
					case 4:
						if(this.selectedObjectDeleted) {
							alert("'" + this.selectedObjectName + "' " + modx.lang.already_deleted)
						} else if(confirm("'" + this.selectedObjectName + "'\n\n" + modx.lang.confirm_delete_resource) === true) {
							m.location.href = "index.php?a=6&id=" + this.itemToChange
						}
						break;
					case 5:
						this.setActiveFromContextMenu(this.itemToChange);
						m.location.href = "index.php?a=51&id=" + this.itemToChange;
						break;
					case 6:
						m.location.href = "index.php?a=72&pid=" + this.itemToChange;
						break;
					case 7:
						if(confirm(modx.lang.confirm_resource_duplicate) === true) {
							m.location.href = "index.php?a=94&id=" + this.itemToChange
						}
						break;
					case 8:
						if(this.selectedObjectDeleted) {
							if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_undelete) === true) {
								m.location.href = "index.php?a=63&id=" + this.itemToChange
							}
						} else {
							alert("'" + this.selectedObjectName + "'" + modx.lang.not_deleted)
						}
						break;
					case 9:
						if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_publish) === true) {
							m.location.href = "index.php?a=61&id=" + this.itemToChange
						}
						break;
					case 10:
						if(this.itemToChange !== modx.config.site_start) {
							if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_unpublish) === true) {
								m.location.href = "index.php?a=62&id=" + this.itemToChange
							}
						} else {
							alert('Document is linked to site_start variable and cannot be unpublished!')
						}
						break;
					case 11:
						m.location.href = "index.php?a=56&id=" + this.itemToChange;
						break;
					case 12:
						w.open(this.selectedObjectUrl, 'previeWin');
						break;
					default:
						alert('Unknown operation command.')
				}
			},
			setSelected: function(a) {
				var el = d.querySelector('#treeRoot .current');
				if(el) el.classList.remove('current');
				if(a) a.classList.add('current')
			},
			setActiveFromContextMenu: function(a) {
				var el = d.querySelector('#node' + a + '>.node');
				if(el) this.setSelected(el)
			},
			setSelectedByContext: function(a) {
				var el = d.querySelector('#treeRoot .selected');
				if(el) el.classList.remove('selected');
				if(a) d.querySelector('#node' + a + '>.node').classList.add('selected');
			},
			setItemToChange: function() {
				var a = d.getElementById(modx.main.idFrame).contentWindow,
					b = a.location.search.substring(1);
				if((parseInt(modx.main.getQueryVariable('a', b)) === 27 || parseInt(modx.main.getQueryVariable('a', b)) === 3) && modx.main.getQueryVariable('id', b)) {
					this.itemToChange = parseInt(modx.main.getQueryVariable('id', b))
				} else {
					this.itemToChange = null
				}
			},
			restoreTree: function() {
				console.log('modx.tree.restoreTree()');
				var el = d.getElementById('buildText');
				if(el) {
					el.innerHTML = modx.style.tree_info + modx.lang.loading_doc_tree;
					el.style.display = 'block'
				}
				this.setItemToChange();
				this.rpcNode = d.getElementById('treeRoot');
				modx.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=2&id=' + this.itemToChange, function(r) {
					modx.tree.rpcLoadData(r)
				})
			},
			expandTree: function() {
				this.rpcNode = d.getElementById('treeRoot');
				var el = d.getElementById('buildText');
				if(el) {
					el.innerHTML = modx.style.tree_info + modx.lang.loading_doc_tree;
					el.style.display = 'block'
				}
				modx.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=1&id=' + this.itemToChange, function(r) {
					modx.tree.rpcLoadData(r);
					modx.tree.saveFolderState();
				})
			},
			collapseTree: function() {
				this.rpcNode = d.getElementById('treeRoot');
				var el = d.getElementById('buildText');
				if(el) {
					el.innerHTML = modx.style.tree_info + modx.lang.loading_doc_tree;
					el.style.display = 'block'
				}
				modx.get('index.php?a=1&f=nodes&indent=1&parent=0&expandAll=0&id=' + this.itemToChange, function(r) {
					modx.openedArray = [];
					modx.tree.saveFolderState();
					modx.tree.rpcLoadData(r)
				})
			},
			updateTree: function() {
				this.rpcNode = d.getElementById('treeRoot');
				var el = d.getElementById('buildText');
				if(el) {
					el.innerHTML = modx.style.tree_info + modx.lang.loading_doc_tree;
					el.style.display = 'block'
				}
				var a = d.sortFrm;
				var b = 'a=1&f=nodes&indent=1&parent=0&expandAll=2&dt=' + a.dt.value + '&tree_sortby=' + a.sortby.value + '&tree_sortdir=' + a.sortdir.value + '&tree_nodename=' + a.nodename.value + '&id=' + this.itemToChange + '&showonlyfolders=' + a.showonlyfolders.value;
				modx.get('index.php?' + b, function(r) {
					modx.tree.rpcLoadData(r)
				})
			},
			getFolderState: function() {
				var a;
				if(modx.openedArray !== [0]) {
					a = "&opened=";
					for(var key in modx.openedArray) {
						if(modx.openedArray[key]) {
							a += key + "|"
						}
					}
				} else {
					a = "&opened="
				}
				return a
			},
			saveFolderState: function() {
				modx.get('index.php?a=1&f=nodes&savestateonly=1' + this.getFolderState())
			},
			showSorter: function(e) {
				e = e || w.event;
				var el = d.getElementById('floater');
				e.target.dataset.toggle = '#floater';
				el.classList.toggle('show');
				el.onclick = function(e) {
					e.stopPropagation()
				}
			},
			emptyTrash: function() {
				if(confirm(modx.lang.confirm_empty_trash) === true) {
					m.location.href = "index.php?a=64"
				}
			},
			showBin: function(a) {
				var el = d.getElementById('treeMenu_emptytrash');
				if(el) {
					if(a) {
						el.title = modx.lang.empty_recycle_bin;
						el.classList.remove('disabled');
						el.innerHTML = modx.style.empty_recycle_bin;
						el.onclick = function() {
							modx.tree.emptyTrash()
						}
					} else {
						el.title = modx.lang.empty_recycle_bin_empty;
						el.classList.add('disabled');
						el.innerHTML = modx.style.empty_recycle_bin_empty;
						el.onclick = null
					}
				}
			},
			unlockElement: function(a, b, c) {
				var m = modx.lockedElementsTranslation.msg.replace('[+id+]', b).replace('[+element_type+]', modx.lockedElementsTranslation['type' + a]);
				if(confirm(m) === true) {
					modx.get('index.php?a=67&type=' + a + '&id=' + b, function(r) {
						if(parseInt(r) === 1) c.parentNode.removeChild(c);
						else alert(r)
					})
				}
			},
			resizeTree: function() {
			},
			reloadElementsInTree: function() {
				modx.get('index.php?a=1&f=tree', function(r) {
					savePositions();
					var div = d.createElement('div');
					div.innerHTML = r;
					var tabs = div.getElementsByClassName('tab-page');
					var el, p;
					for(var i = 0; i < tabs.length; i++) {
						if(tabs[i].id !== 'tabDoc') {
							el = tabs[i].getElementsByClassName('panel-group')[0];
							el.style.display = 'none';
							el.classList.add('clone');
							p = d.getElementById(tabs[i].id);
							r = p.getElementsByClassName('panel-group')[0];
							p.insertBefore(el, r)
						}
					}
					setRememberCollapsedCategories();
					for(var i = 0; i < tabs.length; i++) {
						if(tabs[i].id !== 'tabDoc') {
							el = d.getElementById(tabs[i].id).getElementsByClassName('panel-group')[1];
							el.remove();
							el = d.getElementById(tabs[i].id).getElementsByClassName('panel-group')[0];
							el.classList.remove('clone');
							el.style.display = 'block'
						}
					}
					loadPositions();
					initQuicksearch('tree_site_templates_search', 'tree_site_templates');
					var at = d.querySelectorAll('#tree .accordion-toggle');
					for(var i = 0; i < at.length; i++) {
						at[i].onclick = function(e) {
							e.preventDefault();
							var thisItemCollapsed = $(this).hasClass("collapsed");
							if(e.shiftKey) {
								var toggleItems = $(this).closest(".panel-group").find("> .panel .accordion-toggle");
								var collapseItems = $(this).closest(".panel-group").find("> .panel > .panel-collapse");
								if(thisItemCollapsed) {
									toggleItems.removeClass("collapsed");
									collapseItems.collapse("show")
								} else {
									toggleItems.addClass("collapsed");
									collapseItems.collapse("hide")
								}
								toggleItems.each(function() {
									var state = $(this).hasClass("collapsed") ? 1 : 0;
									setLastCollapsedCategory($(this).data("cattype"), $(this).data("catid"), state)
								});
								writeElementsInTreeParamsToStorage()
							} else {
								$(this).toggleClass("collapsed");
								$($(this).attr("href")).collapse("toggle");
								var state = thisItemCollapsed ? 0 : 1;
								setLastCollapsedCategory($(this).data("cattype"), $(this).data("catid"), state);
								writeElementsInTreeParamsToStorage()
							}
						}
					}
				})
			}
		},
		setLastClickedElement: function(a, b) {
			localStorage.setItem('MODX_lastClickedElement', '[' + parseInt(a) + ',' + parseInt(b) + ']')
		},
		removeLocks: function() {
			if(confirm(modx.lang.confirm_remove_locks) === true) {
				m.location.href = "index.php?a=67"
			}
		},
		openCredits: function() {
			m.location.href = "index.php?a=18";
			setTimeout('modx.main.stopWork()', 2000)
		},
		keepMeAlive: function() {
			modx.get('includes/session_keepalive.php?tok=' + d.getElementById('sessTokenInput').value + '&o=' + Math.random(), function(r) {
				r = JSON.parse(r);
				if(r.status !== 'ok') w.location.href = 'index.php?a=8'
			})
		},
		updateMail: function(a) {
			try {
				if(a) {
					this.post('index.php', {
						updateMsgCount: true
					}, function(r) {
						var c = r.split(','),
							el = d.getElementById('msgCounter');
						if(c[0] > 0) {
							if(el) {
								el.innerHTML = c[0];
								el.style.display = 'block'
							}
						} else {
							if(el) el.style.display = 'none'
						}
						if(c[1] > 0) {
							el = d.getElementById('newMail');
							if(el) {
								el.innerHTML = '<a href="index.php?a=10" target="main">' + modx.style.email + modx.lang.inbox + ' (' + c[0] + ' / ' + c[1] + ')</a>';
								el.style.display = 'block'
							}
						}
						if(modx.config.mail_check_timeperiod > 0) setTimeout('modx.updateMail(true)', 1000 * modx.config.mail_check_timeperiod)
					})
				}
			} catch(oException) {
				setTimeout('modx.updateMail(true)', 1000 * modx.config.mail_check_timeperiod)
			}
		},
		openWindow: function(a) {
			if(typeof a !== 'object') {
				a = {
					"url": a
				}
			}
			if(!a.width) a.width = parseInt(w.innerWidth * 0.9) + 'px';
			if(!a.height) a.height = parseInt(w.innerHeight * 0.8) + 'px';
			if(!a.left) a.left = parseInt(w.innerWidth * 0.05) + 'px';
			if(!a.top) a.top = parseInt(w.innerHeight * 0.1) + 'px';
			if(!a.title) a.title = Math.floor((Math.random() * 999999) + 1);
			if(a.url) {
				if(this.plugins.EVOmodal === 1) {
					top.EVO.modal.show(a)
				} else {
					w.open(a.url, a.title, 'width=' + a.width + ',height=' + a.height + ',top=' + a.top + ',left=' + a.left + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no')
				}
			}
		},
		getWindowDimension: function() {
			var a = 0,
				b = 0,
				c = d.documentElement,
				e = d.body;
			if(typeof(w.innerWidth) === 'number') {
				a = w.innerWidth;
				b = w.innerHeight
			} else if(c && (c.clientWidth || c.clientHeight)) {
				a = c.clientWidth;
				b = c.clientHeight
			} else if(e && (e.clientWidth || e.clientHeight)) {
				a = e.clientWidth;
				b = e.clientHeight
			}
			return {
				'width': a,
				'height': b
			}
		},
		hideDropDown: function(e) {
			e = e || w.event || m.event;
			if(tree.ca === "open" || tree.ca === "") {
				modx.tree.setSelectedByContext();
			}
			if(modx.tree.ctx !== null) {
				d.getElementById(modx.frameset).removeChild(modx.tree.ctx);
				modx.tree.ctx = null
			}
			if(!(/dropdown\-item/.test(e.target.className))
			//&& !(e && ("click" === e.type && /form|label|input|textarea|select/i.test(e.target.tagName)))
			) {
				var els = d.querySelectorAll('.dropdown'),
					n = null,
					t = e.target || e.target.parentNode;
				if(t.dataset.toggle) n = d.querySelector(t.dataset.toggle);
				if(t.classList.contains('dropdown-toggle')) n = t.offsetParent;
				for(var i = 0; i < els.length; i++) {
					if(n !== els[i])
						els[i].classList.remove('show')
				}
				els = m.document.querySelectorAll('.dropdown');
				for(var i = 0; i < els.length; i++) {
					if(n !== els[i])
						els[i].classList.remove('show')
				}
			}
		},
		XHR: function() {
			return ('XMLHttpRequest' in w) ? new XMLHttpRequest : new ActiveXObject('Microsoft.XMLHTTP');
		},
		get: function(a, b) {
			var x = this.XHR();
			x.open('GET', a, true);
			x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			x.onload = function() {
				if(this.status === 200 && typeof b === 'function') {
					return b(this.responseText)
				}
			};
			x.send()
		},
		post: function(a, b, c) {
			var x = this.XHR(),
				f = '';
			if(typeof b === 'function') {
				c = b;
			} else if(typeof b === 'object') {
				var e = [],
					i = 0,
					k;
				for(k in b) {
					if(b.hasOwnProperty(k)) e[i++] = k + '=' + b[k];
				}
				f = e.join('&')
			} else if(typeof b === 'string') {
				f = b;
			}
			x.open('POST', a, true);
			x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			x.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');
			x.onload = function() {
				if(this.readyState === 4 && c !== u) {
					return c(this.responseText)
				}
			};
			x.send(f)
		},
		animation: {
			duration: 300,
			fadeIn: function(a, b, c) {

			},
			fadeOut: function(a, b, c) {

			},
			slideUp: function(a, b, c) {
				if(!a) return;
				if(a.tagName) {
					if(typeof parseInt(b) === 'number' && parseInt(b) >= 0) b = parseInt(b);
					else if(typeof b === 'function') c = b, b = this.duration;
					else b = this.duration;
					var h = a.offsetHeight;
					a.style.overflow = 'hidden';
					b += (h / 1000) * b;
					this.animate(a.firstChild, 'marginTop', 'px', 0, -h, b, function() {
						return c ? c.call(a) : ''
					}, 'slide')
				} else if(typeof a === 'string') {
					var els = d.querySelectorAll(a);
					for(var i = 0; i < els.length; i++) {
						modx.animation.slideUp(els[i], b, c)
					}
				}
			},
			slideDown: function(a, b, c) {
				if(!a) return;
				if(a.tagName) {
					if(typeof parseInt(b) === 'number' && parseInt(b) >= 0) b = parseInt(b);
					else if(typeof b === 'function') (c = b, b = this.duration);
					else b = this.duration;
					var h = a.offsetHeight;
					a.style.overflow = 'hidden';
					a.firstChild.style.marginTop = -h + 'px';
					b += (h / 1000) * b;
					this.animate(a.firstChild, 'marginTop', 'px', -h, 0, b, (function() {
						return c ? c.call(a) : '';
					}), 'slide')
				} else if(typeof a === 'string') {
					var els = d.querySelectorAll(a);
					for(var i = 0; i < els.length; i++) {
						modx.animation.slideDown(els[i], b, c)
					}
				}
			},
			animate: function(a, b, c, d, e, f, k, l) {
				if(!a) return;
				var g = Date.now();
				clearInterval((!a.timers ? (a.timers = [], a.timers[l] = 0) : a.timers[l]));
				a.timers[l] = setInterval(function() {
					var i = Math.min(1, (Date.now() - g) / f);
					a.style[b] = (d + i * (e - d)) + c;
					1 === i ? (clearInterval(a.timers[l]), k()) : ''
				})
			}
		}
	};
	for(var o in _) modx[o] = _[o];
	_ = '';
	w.mainMenu = {};
	w.mainMenu.stopWork = function() {
		modx.main.stopWork()
	};
	w.mainMenu.work = function() {
		modx.main.work()
	};
	w.mainMenu.reloadtree = function() {
		//console.log('mainMenu.reloadtree()');
		setTimeout('modx.tree.restoreTree()', 50)
	};
	w.mainMenu.startrefresh = function(a) {
		if(a === 1) {
			//console.log('mainMenu.startrefresh(' + a + ')');
			modx.tree.restoreTree()
		}
		if(a === 2) {
			//console.log('mainMenu.startrefresh(' + a + ')');
			modx.tree.restoreTree()
		}
		if(a === 9) {
			//console.log('mainMenu.startrefresh(' + a + ')');
			modx.tree.restoreTree()
		}
		if(a === 10) {
			//console.log('mainMenu.startrefresh(' + a + ')');
			w.location.href = "../" + modx.MGR_DIR
		}
	};
	w.mainMenu.startmsgcount = function(a, b, c) {
		modx.updateMail(c)
	};
	w.tree = {};
	w.tree.ca = 'open';
	w.tree.document = document;
	w.tree.saveFolderState = function() {
	};
	w.tree.updateTree = function() {
		//console.log('tree.updateTree()');
		modx.tree.updateTree()
	};
	w.tree.restoreTree = function() {
		//console.log('tree.restoreTree()');
		modx.tree.restoreTree()
	};
	w.tree.reloadElementsInTree = function() {
		//console.log('tree.reloadElementsInTree()');
		modx.tree.reloadElementsInTree()
	};
	w.tree.resizeTree = function() {
		console.log('tree.resizeTree() off')
	};
	w.onbeforeunload = function() {
		var a = d.getElementById(modx.main.idFrame).contentWindow;
		if(parseInt(modx.main.getQueryVariable('a', a.location.search.substring(1))) === 27) {
			modx.get('index.php?a=67&type=7&id=' + modx.main.getQueryVariable('id', a.location.search.substring(1)));
		}
	};
	d.addEventListener('DOMContentLoaded', function() {
		modx.init()
	})
})
(jQuery, window, document, window.main, undefined);

function setLastClickedElement(a, b) {
	modx.setLastClickedElement(a, b)
}

function reloadElementsInTree() {
	modx.tree.reloadElementsInTree()
}

(function() {
	if(!Element.prototype.closest) {
		Element.prototype.closest = function(a) {
			var b = this,
				c, d;
			['matches', 'webkitMatchesSelector', 'mozMatchesSelector', 'msMatchesSelector', 'oMatchesSelector'].some(function(fn) {
				if(typeof document.body[fn] === 'function') {
					c = fn;
					return true
				}
				return false
			});
			if(b && b[c](a)) return b;
			while(b) {
				d = b.parentElement;
				if(d && d[c](a)) return d;
				b = d
			}
			return null;
		}
	}
})();
