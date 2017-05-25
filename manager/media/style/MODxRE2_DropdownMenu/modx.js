'use strict';
(function($, w, d, u) {
	let _ = {
		frameset: 'frameset',
		init: function() {
			if(!localStorage.getItem('MODX_lastPositionSideBar')) {
				localStorage.setItem('MODX_lastPositionSideBar', this.config.tree_width)
			}
			this.main.stopWork();
			this.tree.init();
			this.mainMenu.init();
			this.resizer.init();
			this.setLastClickedElement(0, 0);
			w.setInterval(this.keepMeAlive, 1000 * 60 * this.config.session_timeout);
			w.onload = this.updateMail(true);
			d.onclick = this.hideDropDown
		},
		mainMenu: {
			id: 'mainMenu',
			init: function() {
				//console.log('modx.mainMenu.init()');
				d.getElementById(this.id).onmouseover = function() {
					let el = this.querySelector('.close');
					if(el) el.classList.remove('close');
					this.style.overflow = '';
				};
				d.getElementById(this.id).onclick = function(e) {
					let t = e.target.closest('a');
					if(t !== null && t.href !== '' && t.href !== this.baseURI) {
						this.querySelector('.active').classList.remove('active');
						if(t.offsetParent.className.indexOf('dropdown-menu') === 0) {
							t.offsetParent.offsetParent.classList.add('active')
						} else {
							t.offsetParent.classList.add('active')
						}
						this.style.overflow = 'hidden'
					}
				};
				modx.search.init();
				let elms = d.querySelectorAll('#' + this.id + ' .nav > li > ul');
				for(let i = 0; i < elms.length; i++) {
					elms[i].style.maxHeight = w.innerHeight - modx.config.menu_height + 'px'
				}
			}
		},
		search: {
			id: 'searchform',
			idResult: 'searchresult',
			idInput: 'searchid',
			classResult: 'ajaxSearchResults',
			classMask: 'mask',
			searchResultWidth: '400',
			timer: 0,
			init: function() {
				this.result = d.getElementById(this.idResult);
				this.result.style.width = this.searchResultWidth + 'px';
				this.result.style.marginRight = -this.searchResultWidth + 'px';
				let t = this,
					el = d.getElementById(this.idInput),
					r = d.createElement('i');
				r.className = 'fa fa-refresh fa-spin fa-fw';
				el.onkeyup = function(e) {
					e.preventDefault();
					clearTimeout(t.timer);
					if(el.value.length !== '' && el.value.length > 2) {
						t.timer = setTimeout(function() {
							let xhr = modx.XHR();
							xhr.open('GET', 'index.php?a=71&ajax=1&submitok=Search&searchid=' + el.value, true);
							xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
							xhr.onload = function() {
								if(this.status === 200) {
									modx.animation.fadeOut(r, true);
									let div = d.createElement('div');
									div.innerHTML = this.responseText;
									let o = div.getElementsByClassName(t.classResult)[0];
									if(o) {
										if(o.innerHTML !== '') {
											let p = o.getElementsByTagName('A');
											for(let i = 0; i < p.length; i++) {
												p[i].target = 'main';
												p[i].innerHTML += '<i onclick="modx.openWindow({title:\'' + p[i].innerText + '\',id:\'' + p[i].id + '\',url:\'' + p[i].href + '\'});return false;">' + modx.style.icons_external_link + '</i>'
											}
											t.result.innerHTML = o.outerHTML;
											t.open();
											t.result.onclick = function(e) {
												if(e.target.tagName === 'I') {
													return false
												}
												let a = e.target.closest('a');
												if(a !== null) {
													let el = t.result.querySelector('.selected');
													if(el) el.className = '';
													a.className = 'selected'
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
								el.closest('form').appendChild(r)
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
			init: function() {
				modx.main.stopWork();
				modx.main.scrollWork()
			},
			work: function() {
				let el = d.getElementById('workText');
				if(el) {
					el.innerHTML = modx.style.icons_working + modx.lang.working;
					el.style.display = 'block';
				}
				else setTimeout('modx.main.work()', 50)
			},
			stopWork: function() {
				let el = d.getElementById('workText');
				if(el) {
					modx.animation.fadeOut(el);
					w.main.onclick = modx.hideDropDown;
				}
				else setTimeout('modx.main.stopWork()', 50)
			},
			scrollWork: function() {
				let a = d.getElementById(modx.main.idFrame).contentWindow,
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
				let vars = q.split('&');
				for(let i = 0; i < vars.length; i++) {
					let p = vars[i].split('=');
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
			background: '#bbb',
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
				let p = parseInt(d.getElementById('tree').offsetWidth) !== 0 ? 0 : (localStorage.getItem('MODX_lastPositionSideBar') ? parseInt(localStorage.getItem('MODX_lastPositionSideBar')) : modx.config.tree_width);
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
			toggleNode: function(a, b, c, e, f) {
				f = (!f || f === '0') ? '0' : '1';
				this.rpcNode = a.parentNode.lastChild;
				let rpcNodeText,
					loadText = modx.lang.loading_doc_tree,
					iconNodeToggle = d.getElementById("s" + c),
					iconNode = d.getElementById("f" + c);
				if(this.rpcNode.innerHTML === '') {
					iconNodeToggle.innerHTML = iconNodeToggle.dataset.iconCollapsed;
					iconNode.innerHTML = iconNode.dataset.iconFolderOpen;
					rpcNodeText = this.rpcNode.innerHTML;
					modx.openedArray[c] = 1;
					if(rpcNodeText === "" || rpcNodeText.indexOf(loadText) > 0) {
						let folderState = this.getFolderState();
						let el = d.getElementById('buildText');
						if(el) {
							el.innerHTML = modx.style.tree_info + loadText;
							el.style.display = 'block'
						}
						modx.get('index.php?a=1&f=nodes&indent=' + b + '&parent=' + c + '&expandAll=' + e + folderState, function(r) {
							modx.tree.rpcLoadData(r)
						})
					}
					this.saveFolderState()
				} else {
					iconNodeToggle.innerHTML = iconNodeToggle.dataset.iconExpanded;
					iconNode.innerHTML = iconNode.dataset.iconFolderClose;
					delete modx.openedArray[c];
					this.rpcNode.innerHTML = '';
					this.saveFolderState()
				}
			},
			rpcLoadData: function(a) {
				if(this.rpcNode !== null) {
					this.rpcNode.innerHTML = typeof a === 'object' ? a.responseText : a;
					this.rpcNode.loaded = true;
					let el = d.getElementById('buildText');
					if(el) {
						modx.animation.fadeOut(el)
					}
					if(this.rpcNode.id === 'treeRoot') {
						el = d.getElementById('binFull');
						if(el) this.showBin(true);
						else this.showBin(false)
					}
					el = d.getElementById('mx_loginbox');
					if(el) {
						this.rpcNode.innerHTML = '';
						w.location = 'index.php'
					}
				}
			},
			treeAction: function(e, a, b, c, f, g) {
				if(tree.ca === "move") {
					try {
						this.setSelectedByContext(a);
						w.main.setMoveValue(a, b)
					} catch(oException) {
						alert(modx.lang.unable_set_parent)
					}
				}
				if(tree.ca === "open" || tree.ca === "") {
					if(a === 0) {
						w.main.location.href = "index.php?a=2"
					} else {
						let href = '';
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
							d.getElementById('s' + a).onclick()
						}

						if(href) {
							if(e.shiftKey) {
								w.getSelection().removeAllRanges();
								modx.openWindow(href);
								this.restoreTree()
							} else {
								w.main.location.href = href
							}
						}
					}
					let el = d.querySelector('#node' + a + '>.treeNode');
					modx.tree.setSelected(el)
				}
				if(tree.ca === "parent") {
					try {
						this.setSelectedByContext(a);
						w.main.setParent(a, b)
					} catch(oException) {
						alert(modx.lang.unable_set_parent)
					}
				}
				if(tree.ca === "link") {
					try {
						this.setSelectedByContext(a);
						w.main.setLink(a)
					} catch(oException) {
						alert(modx.lang.unable_set_link)
					}
				}
			},
			showPopup: function(a, b, c, f, g, e) {
				let node = d.getElementById('node' + a),
					tree = d.getElementById('tree');

				if(node.dataset.contextmenu) {
					modx.hideDropDown();
					this.ctx = d.createElement('div');
					this.ctx.id = 'contextmenu';
					this.ctx.className = 'dropdown-menu';
					d.getElementById(modx.frameset).appendChild(this.ctx);
					this.setSelectedByContext(a);
					let dataJson = JSON.parse(node.dataset.contextmenu);
					for(let key in dataJson) {
						if(dataJson.hasOwnProperty(key)) {
							let item = d.createElement('div');
							for(let k in dataJson[key]) {
								if(dataJson[key].hasOwnProperty(k)) {
									if(k.substring(0, 2) === 'on') {
										let onEvent = dataJson[key][k];
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
					let bodyHeight = tree.offsetHeight + tree.offsetTop;
					let x = e.clientX > 0 ? e.clientX : e.pageX;
					let y = e.clientY > 0 ? e.clientY : e.pageY;
					if(y + this.ctx.offsetHeight / 2 > bodyHeight) {
						y = bodyHeight - this.ctx.offsetHeight - 5
					} else if(y - this.ctx.offsetHeight / 2 < tree.offsetTop) {
						y = tree.offsetTop + 5
					} else {
						y = y - this.ctx.offsetHeight / 2
					}
					let el = e.target.closest('.treeNode');
					if(el === null) x += 50;
					this.itemToChange = a;
					this.selectedObjectName = b;
					this.dopopup(this.ctx, x + 10, y);
					this.ctx.classList.add('show');
					e.stopPropagation()
				} else {
					let ctx = d.getElementById('mx_contextmenu');
					modx.hideDropDown(ctx);
					this.setSelectedByContext(a);
					let i4 = d.getElementById('item4'),
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
					let bodyHeight = tree.offsetHeight + tree.offsetTop;
					let x = e.clientX > 0 ? e.clientX : e.pageX;
					let y = e.clientY > 0 ? e.clientY : e.pageY;
					if(y + ctx.offsetHeight / 2 > bodyHeight) {
						y = bodyHeight - ctx.offsetHeight - 5
					} else if(y - ctx.offsetHeight / 2 < tree.offsetTop) {
						y = tree.offsetTop + 5
					} else {
						y = y - ctx.offsetHeight / 2
					}
					let el = e.target.closest('.treeNode');
					if(el === null) x += 50;
					this.itemToChange = a;
					this.selectedObjectName = b;
					this.dopopup(ctx, x + 10, y);
					e.stopPropagation()
				}
			},
			dopopup: function(el, a, b) {
				if(this.selectedObjectName.length > 20) {
					this.selectedObjectName = this.selectedObjectName.substr(0, 20) + "..."
				}
				let f = d.getElementById("nameHolder");
				el.style.left = a + (modx.config.textdir ? '-190' : '') + "px";
				el.style.top = b + "px";
				el.classList.add('show');
				f.innerHTML = this.selectedObjectName
			},
			menuHandler: function(a) {
				switch(a) {
					case 1:
						this.setActiveFromContextMenu(this.itemToChange);
						w.main.location.href = "index.php?a=3&id=" + this.itemToChange;
						break;
					case 2:
						this.setActiveFromContextMenu(this.itemToChange);
						w.main.location.href = "index.php?a=27&r=1&id=" + this.itemToChange;
						break;
					case 3:
						w.main.location.href = "index.php?a=4&pid=" + this.itemToChange;
						break;
					case 4:
						if(this.selectedObjectDeleted) {
							alert("'" + this.selectedObjectName + "' " + modx.lang.already_deleted)
						} else if(confirm("'" + this.selectedObjectName + "'\n\n" + modx.lang.confirm_delete_resource) === true) {
							w.main.location.href = "index.php?a=6&id=" + this.itemToChange
						}
						break;
					case 5:
						w.main.location.href = "index.php?a=51&id=" + this.itemToChange;
						break;
					case 6:
						w.main.location.href = "index.php?a=72&pid=" + this.itemToChange;
						break;
					case 7:
						if(confirm(modx.lang.confirm_resource_duplicate) === true) {
							w.main.location.href = "index.php?a=94&id=" + this.itemToChange
						}
						break;
					case 8:
						if(this.selectedObjectDeleted) {
							if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_undelete) === true) {
								w.main.location.href = "index.php?a=63&id=" + this.itemToChange
							}
						} else {
							alert("'" + this.selectedObjectName + "'" + modx.lang.not_deleted)
						}
						break;
					case 9:
						if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_publish) === true) {
							w.main.location.href = "index.php?a=61&id=" + this.itemToChange
						}
						break;
					case 10:
						if(this.itemToChange !== modx.config.site_start) {
							if(confirm("'" + this.selectedObjectName + "' " + modx.lang.confirm_unpublish) === true) {
								w.main.location.href = "index.php?a=62&id=" + this.itemToChange
							}
						} else {
							alert('Document is linked to site_start variable and cannot be unpublished!')
						}
						break;
					case 11:
						w.main.location.href = "index.php?a=56&id=" + this.itemToChange;
						break;
					case 12:
						w.open(this.selectedObjectUrl, 'previeWin');
						break;
					default:
						alert('Unknown operation command.')
				}
			},
			setSelected: function(a) {
				let el = d.querySelector('.treeNodeSelected');
				if(el) el.classList.remove('treeNodeSelected');
				if(a) a.classList.add('treeNodeSelected')
			},
			setActiveFromContextMenu: function(a) {
				let el = d.querySelector('#node' + a + '>.treeNode');
				if(el) this.setSelected(el)
			},
			setSelectedByContext: function(a) {
				let el = d.querySelector('#tree .treeNodeSelectedByContext');
				if(el) el.classList.remove('treeNodeSelectedByContext');
				if(a) d.querySelector('#node' + a + '>.treeNode').classList.add('treeNodeSelectedByContext');
			},
			setItemToChange: function() {
				let a = d.getElementById(modx.main.idFrame).contentWindow, b = a.location.search.substring(1);
				if((parseInt(modx.main.getQueryVariable('a', b)) === 27 || parseInt(modx.main.getQueryVariable('a', b)) === 3) && modx.main.getQueryVariable('id', b)) {
					this.itemToChange = parseInt(modx.main.getQueryVariable('id', b))
				} else {
					this.itemToChange = null
				}
			},
			restoreTree: function() {
				console.log('modx.tree.restoreTree()');
				let el = d.getElementById('buildText');
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
				let el = d.getElementById('buildText');
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
				let el = d.getElementById('buildText');
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
				let el = d.getElementById('buildText');
				if(el) {
					el.innerHTML = modx.style.tree_info + modx.lang.loading_doc_tree;
					el.style.display = 'block'
				}
				let a = d.sortFrm;
				let b = 'a=1&f=nodes&indent=1&parent=0&expandAll=2&dt=' + a.dt.value + '&tree_sortby=' + a.sortby.value + '&tree_sortdir=' + a.sortdir.value + '&tree_nodename=' + a.nodename.value + '&id=' + this.itemToChange + '&showonlyfolders=' + a.showonlyfolders.value;
				modx.get('index.php?' + b, function(r) {
					modx.tree.rpcLoadData(r)
				})
			},
			getFolderState: function() {
				let a;
				if(modx.openedArray !== [0]) {
					a = "&opened=";
					for(let key in modx.openedArray) {
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
				let el = d.getElementById('floater');
				el.classList.toggle('show');
				el.onclick = function(e) {
					e.stopPropagation()
				};
				modx.hideDropDown(el);
				e.stopPropagation()
			},
			emptyTrash: function() {
				if(confirm(modx.lang.confirm_empty_trash) === true) {
					w.main.location.href = "index.php?a=64"
				}
			},
			showBin: function(a) {
				let el = d.getElementById('treeMenu_emptytrash');
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
				let m = modx.lockedElementsTranslation.msg.replace('[+id+]', b).replace('[+element_type+]', modx.lockedElementsTranslation['type' + a]);
				if(confirm(m) === true) {
					modx.get('index.php?a=67&type=' + a + '&id=' + b, function(r) {
						if(parseInt(r) === 1) modx.animation.fadeOut(c, true);
						else alert(r)
					})
				}
			},
			resizeTree: function() {
			},
			reloadElementsInTree: function() {
				modx.get('index.php?a=1&f=tree', function(r) {
					savePositions();
					let div = d.createElement('div');
					div.innerHTML = r;
					let tabs = div.getElementsByClassName('tab-page');
					let el, p;
					for(let i = 0; i < tabs.length; i++) {
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
					for(let i = 0; i < tabs.length; i++) {
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
					let at = d.querySelectorAll('#tree .accordion-toggle');
					for(let i = 0; i < at.length; i++) {
						at[i].onclick = function(e) {
							e.preventDefault();
							let thisItemCollapsed = $(this).hasClass("collapsed");
							if(e.shiftKey) {
								let toggleItems = $(this).closest(".panel-group").find("> .panel .accordion-toggle");
								let collapseItems = $(this).closest(".panel-group").find("> .panel > .panel-collapse");
								if(thisItemCollapsed) {
									toggleItems.removeClass("collapsed");
									collapseItems.collapse("show")
								} else {
									toggleItems.addClass("collapsed");
									collapseItems.collapse("hide")
								}
								toggleItems.each(function() {
									let state = $(this).hasClass("collapsed") ? 1 : 0;
									setLastCollapsedCategory($(this).data("cattype"), $(this).data("catid"), state)
								});
								writeElementsInTreeParamsToStorage()
							} else {
								$(this).toggleClass("collapsed");
								$($(this).attr("href")).collapse("toggle");
								let state = thisItemCollapsed ? 0 : 1;
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
				w.main.location.href = "index.php?a=67"
			}
		},
		openCredits: function() {
			w.main.location.href = "index.php?a=18";
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
						let c = r.split(','),
							el = d.getElementById('msgCounter');
						if(c[0] > 0) {
							if(el) {
								el.innerHTML = c[0];
								modx.animation.fadeIn(el)
							}
						} else {
							if(el) modx.animation.fadeOut(el)
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
			if(a.width === u) a.width = parseInt(w.innerWidth * 0.9) + 'px';
			if(a.height === u) a.height = parseInt(w.innerHeight * 0.8) + 'px';
			if(a.left === u) a.left = parseInt(w.innerWidth * 0.05) + 'px';
			if(a.top === u) a.top = parseInt(w.innerHeight * 0.1) + 'px';
			if(a.title === u) a.title = Math.floor((Math.random() * 999999) + 1);
			if(a.url !== u) {
				if(this.plugins.EVOmodal === 1) {
					top.EVO.modal.show(a)
				} else {
					w.open(a.url, a.title, 'width=' + a.width + ',height=' + a.height + ',top=' + a.top + ',left=' + a.left + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no')
				}
			}
		},
		getWindowDimension: function() {
			let a = 0,
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
		hideDropDown: function(a) {
			let elms = d.getElementsByClassName('dropdown-menu');
			for(let i = 0; i < elms.length; i++) {
				if(a === null || (a !== null && a !== elms[i])) elms[i].classList.remove('show')
			}
			if(tree.ca === "open" || tree.ca === "") {
				modx.tree.setSelectedByContext();
			}
			if(modx.tree.ctx !== null) {
				d.getElementById(modx.frameset).removeChild(modx.tree.ctx);
				modx.tree.ctx = null
			}
		},
		animation: {
			fadeIn: function(a, b) {
				a.style.opacity = 0;
				a.style.display = b || "block";
				(function fade() {
					let val = parseFloat(a.style.opacity);
					if(!((val += .05) >= 1)) {
						a.style.opacity = val;
						requestAnimationFrame(fade)
					}
				})()
			},
			fadeOut: function(a, b) {
				a.style.opacity = 1;
				(function fade() {
					if((a.style.opacity -= .05) <= 0) {
						a.style.display = '';
						if(b && a.parentElement) {
							a.parentElement.removeChild(a);
							a.style.display = '';
							a.style.opacity = 1
						}
					} else {
						requestAnimationFrame(fade)
					}
				})()
			}
		},
		XHR: function() {
			return ('XMLHttpRequest' in w) ? new XMLHttpRequest : new ActiveXObject('Microsoft.XMLHTTP');
		},
		get: function(a, b) {
			let x = this.XHR();
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
			let x = this.XHR(),
				f = '';
			if(typeof b === 'function') c = b;
			if(typeof b === 'object') {
				let e = [],
					i = 0,
					k;
				for(k in b) {
					if(b.hasOwnProperty(k)) e[i++] = k + '=' + b[k];
				}
				f = e.join('&')
			}
			x.open('POST', a, true);
			x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			x.onload = function() {
				if(this.status === 200 && c !== u) {
					return c(this.responseText)
				}
			};
			x.send(f)
		}
	};
	for(let o in _) modx[o] = _[o];
	_ = '';
	w.mainMenu = {};
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
		if(a === 9 || a === 10) {
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
		let a = d.getElementById(modx.main.idFrame).contentWindow;
		if(parseInt(modx.main.getQueryVariable('a', a.location.search.substring(1))) === 27) {
			modx.get('index.php?a=67&type=7&id=' + modx.main.getQueryVariable('id', a.location.search.substring(1)));
		}
	};
	d.addEventListener('DOMContentLoaded', function() {
		modx.init()
	})
})(typeof(jQuery) !== 'undefined' ? jQuery : '', window, document, undefined);

function setLastClickedElement(a, b) {
	modx.setLastClickedElement(a, b)
}

(function() {
	if(!Element.prototype.closest) {
		Element.prototype.closest = function(a) {
			let b = this, c, d;
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