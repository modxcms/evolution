/*
Script: moostick dot js
    A Mootools-powered, unobtrusive, Javascript news ticker library. /mouthful

About:
    <Moostick> will take in any type of li-containing element and turn it into
    an animated news ticker, fading between each headline at a set interval.

Version:
    - Version: 1.0

Homepage:
    - Moostick @ tekArtist <http://tekartist.org/labs/mootools/moostick/>

Requirements:
    - Cascading Style Sheets (CSS): <http://www.w3.org/Style/CSS/>
    - Javascript: <http://en.wikipedia.org/wiki/JavaScript>
    - MooTools: <http://mootools.net/>
    
Copyright:
    copyright (c) 2007 Stephane Daury: <http://stephane.daury.org/>

License: 
    MIT License <http://www.opensource.org/licenses/mit-license.php>
    
    Copyright (c) 2007 Stephane Daury <http://stephane.daury.org/>
    
    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.

Notes: Procedural code
    Safety check    - Moostick checks that MooTools is present to avoid
                      errors and prompts to either cancel or be redirected
                      to the MooTools site for more info.
    Auto start mode - Moostick auto-starts by default, unless passed init=0
                      in the script's query string for more selective
                      interaction. <MstkHelpers> must be implemented before
                      this step.
*/

if(typeof(window['MooTools']) == 'undefined'){

    // See: <Procedural code>
    
    var mstkRedirect = 'http:/www.mootools.net/';

    if(confirm('Moostick requires the MooTools JavaScript framework.\n'
               + 'Be sure to include it before Moostick.\n\n'
               + 'Click OK for more info, or Cancel to load the page without it.')){
        top.location.href = redirect;
    }
}
else{

    /*
    Class: MstkHelpers 
        (singleton) Utility class containing helper methods that will be helpful in other
        classes.
       
    Notes:
       - Can be used before "ondomready" or "onload" are achieved, and is
         currently required by the auto-start procedure.
       - Can be used directly or have its methods implemented other Classes.
    
    Example:
        (start code)
            alert(MstkHelpers.qsVar('myKey'));
        (end)
    */
    var MstkHelpers = {
        
        // Group: Public Methods
        
        /*
        Method: qsVar
            Query string helper for the Moostick script (NOT the document
            that included it). Takes a query string key, and returns the
            value, or false if not found.    
        
        Arguments:
            key - the key (key=val) to search for, as string.
        
        Returns:
            val - the key's value as string or false if not found.
        */
        qsVar: function(key){
            var val = false;
            if(key.length > 0){
                $ES('script').each(function(s){
                    if(s.src.match('moostick.js?')){
                        var uriParts = s.src.split('?');
                        if(uriParts[1]){
                            uriParts[1].split('&').each(function(keyPair){
                                var tmp = keyPair.split('=');
                                if(tmp[1] && tmp[0] == key)
                                    val = tmp[1];
                            });
                        }
                    }
                });
            }
            return val;
        },
        
        /*
        Method: checkArray
            Checks if the sent value is an array or convert it. Very useful
            when you do not know if you are dealing with an single element
            through $()/$E()/etc or multiple elements through $$()/$E()/etc.
        
        Arguments:
            val - the value to check
        
        Returns:
            val - the sent value if already an array, or a single-item array.
        */
        checkArray: function(val){
            if(!val[0])
                return [val];
            else
                return val;
        }
    
    } // end MstkHelpers


    // See: <Procedural code>
    
    if(MstkHelpers.qsVar('init') !== 'false'){
        window.addEvent('domready', function() {
            MstkInit.go(false, true);
        });
    }


    /*
    Class: MstkInit
        (singleton) A multi-list utility wrapper for the Moostick class and processes.
       
    Notes:
       - Used in auto-start mode to scan the DOM for targeted elements.
       - Also supports concept of safe v. fast mode, like <MooStick>.
       - When opting to turn off auto-start, it is advised to instantiate
         the <Moostick> class directly with a single known list. On the other
         hand, MstkInit will let you pass multiple ones, such as when dealing
         with css classes instead of ids, etc.    
    
    Example:
        (start code)
            // Enable Moostick on any element with "my-custom-class" css class.
            MstkInit.go($$('.my-custom-class'), true);
        (end)
    */
    var MstkInit = {
        
        // Group: Public Properties
        
        /*
        Property: lists
        	One or more list (ul, ol...) from $(), $$(), etc.
        	If passed as false, MstkInit will try to search the
			DOM for list with an id of moostick, or lists with
			a class of moostick.
        */
        lists        : false,
        
        /*
        Property: autoStart
        	Boolean. Should the constructor launch the DOM
        	modification process, or just set the properties.
        */
        autoStart    : false,
        
        /*
        Property: interval
        	Passthru variable. See: <Moostick.interval>.
        */
        interval     : 3500,
        
        /*
        Property: fxOptions
        	Passthru variable. See: <Moostick.fxOptions>.
        */
        fxOptions    : {},
        
        /*
        Property: trust4Speed
        	Set to true to bypass element validation, for increased
        	rendering speed, but only if you're absolutely sure
			about the <MstkInit.lists> you passed in.
        */
        trust4Speed  : false,
        
        /*
        Property: trustOpacity
        	Passthru variable. See: <Moostick.trustOpacity>.
        */
        trustOpacity : false,
        
        // Group: Public Methods
        
        /*
        Method: go
            Sets the default properties and launches the Moostick modification
            process if in auto-start mode.
        
        Arguments:
         	lists        - See: <MstkInit.lists>
        	autoStart    - See: <MstkInit.autoStart>
        	interval     - See: <MstkInit.interval>
        	fxOptions    - See: <MstkInit.fxOptions>
        	trust4Speed  - See: <MstkInit.trust4Speed>
        	trustOpacity - See: <MstkInit.trustOpacity>
        */
        go: function(lists, autoStart, interval, fxOptions, trust4Speed, trustOpacity) {
            this.lists        = (!lists)
                              ? this.lists
                              : lists;
    
            this.autoStart    = (autoStart === true)
                              ? autoStart
                              : this.autoStart;
            
            this.interval     = (!interval) || (interval.toInt() < 500)
                              ? this.interval
                              : interval.toInt();
            
            this.fxOptions    = ((!fxOptions) || (typeof(fxOptions) != 'object'))
                              ? this.fxOptions
                              : fxOptions;
            
            this.trust4Speed  = (trust4Speed === true)
                              ? trust4Speed
                              : this.trust4Speed;
            
            this.trustOpacity = (trustOpacity === true)
                              ? trustOpacity
                              : this.trustOpacity;
    
            if(this.autoStart === true){
                if(this.trust4Speed === true){
                    // Kamikaze mode!
                    this._fastMode();
                }
                else{
                    // Cautious mode
                    this._safeMode();
                }
            }
        },
        
        // Group: Private Methods
        
        /*
        Method: _fastMode
            AKA the Kamikaze mode. Instantiate a <Moostick> object for each
			<MstkInit.lists>, without any validation, besides making sure
			we have an array to loop on.
        */
        _fastMode: function(){
            this.lists = MstkHelpers.checkArray(this.lists);
            this.lists.each(function(list){
                new Moostick(
                    list,
                    this.autoStart,
                    this.interval,
                    this.fxOptions,
                    this.trust4Speed,
                    this.trustOpacity
                )
            }, this);
        },
        
        /*
        Method: _safeMode
            AKA the cautious mode. First validates the passed <MstkInit.lists>
            or tries to auto-discover targeted DOM elements with "moostick"
			as an id or class, then calls <Moostick> for each list.
        */
        _safeMode: function(){
            
            if(this.lists === false){
                var defaultLists = false;
                
                // Scan for a list with the recommended default id
                if($('moostick'))
                    defaultLists = [$('moostick')];
    
                // Or can for (a) list(s) with the recommended default class
                else if($$('.moostick'))
                    defaultLists = $$('.moostick');
                
                this.lists = (!defaultLists) ? false : defaultLists;
            }
     
            if(this.lists){
                this.lists = MstkHelpers.checkArray(this.lists);
                this.lists.each(function(list){
                    new Moostick(
                        list,
                        this.autoStart,
                        this.interval,
                        this.fxOptions,
                        this.trust4Speed,
                        this.trustOpacity
                    )
                }, this);
            }
        }
    
    } // end MstkInit


    /*
    Class: Moostick
        Modifies the presentation and behaviour of li-containing elements to
        act as pseudo news tickers, and extends their DOM with itself
		(eg: myUL.moostick) for easy manipulation.
    
    Example: Programming
        (start code)
            // Auto-discovery mode, looking for li-containing elements with
			// an id (single) or class of "moostick", then auto-starts.
            new Moostick();
            $('moostick').moostick.stopTick();

            // Instantiate Moostick for the "my-custom-id" element. Do
			// not auto-start. Fade every 5 seconds, for 1/2 second.
			// Trust the list I sent, but handle the opacity assignment.
            var myList = new Moostick(
            	$('my-custom-id'),
				false,
				5000,
				{duration: 500},
				true,
				false
            );
            myList.startTick();
        (end)
    */
    var Moostick = Class({
        
        // Group: Public Properties
        
        /*
        Property: version
        	Moostick release version
        */
        version         : '1.0',
        
        /*
        Property: list
        	A single list (ul, ol...) from $(), $E(), etc.
        	This is the element to which Mostick will attach
        	itself. EG: myUL.moostick.stopTick();
        */
        list         : {},
        
        /*
        Property: autoStart
        	Boolean. Should the constructor launch the DOM
        	modification process, or just set the properties.
        */
        
        autoStart    : false,
        /*
        Property: interval
        	Defines duration for which each headline is
        	displayed including transition time, which
        	can be adjusted through <Moostick.fxOptions>.
        */
        
        interval     : 3500,
        
        /*
        Property: fxOptions
        	MooTools effect options for the opacity change used
        	in the transition. See the MooTools docs for details.
        	<http://docs.mootools.net/Effects/Fx-Base.js#Fx.Base>
        */
        fxOptions    : {},
        
        /*
        Property: trust4Speed
        	Set to true to bypass element validation, for
            increased rendering speed, but only if you're
            absolutely sure about the <Moostick.list> you
            passed in. See also: <Moostick.trustOpacity>.
        */
        trust4Speed  : false,
        
        /*
        Property: trustOpacity
        	Set to true to bypass element styles validation,
            for increased rendering speed, but only if you're
            absolutely sure about the <Moostick.list> you sent.
            When ON, we do not loop through each li to set
            the opacity to 0, therefore assuming you dealt
            with it in your stylesheets.
        */
        trustOpacity : false,
        
        // Group: Private Properties
        
        /*
        Property: _firstRun
        	True if the first time we run.
        */
        _firstRun    : true,
        
        /*
        Property: _elSched
        	Element schedule object.
        */
        _elSched     : null,
        
        /*
        Property: _fx
        	Opacity change effect.
        */
        _fx: false,
        
        // Group: Public Methods

        /*
        Method: initialize
            MooTools pseudo constructor. Sets the default properties and
            launches the Moostick modification process if in auto-start mode.
        
        Arguments:
         	list         - See: <Moostick.list>
        	autoStart    - See: <Moostick.autoStart>
        	interval     - See: <Moostick.interval>
        	fxOptions    - See: <Moostick.fxOptions>
        	trust4Speed  - See: <Moostick.trust4Speed>
        	trustOpacity - See: <Moostick.trustOpacity>
        */
        initialize: function(list, autoStart, interval, fxOptions, trust4Speed, trustOpacity) {
            this.list         = ((!list) || (typeof(list) != 'object'))
                              ? this.list
                              : list;
    
            this.autoStart    = (autoStart === true)
                              ? autoStart
                              : this.autoStart;
            
            this.interval     = (!interval) || (interval.toInt() < 500)
                              ? this.interval
                              : interval.toInt();
            
            this.fxOptions    = ((!fxOptions) || (typeof(fxOptions) != 'object'))
                              ? this.fxOptions
                              : fxOptions;
            
            this.trust4Speed  = (trust4Speed === true)
                              ? trust4Speed
                              : this.trust4Speed;
            
            this.trustOpacity = (trustOpacity === true)
                              ? trustOpacity
                              : this.trustOpacity;
            
            this.list.moostick = this;
            
            if(this.autoStart === true){
                if(this.trust4Speed === true){
                    // Kamikaze mode!
                    this._fastMode();
                }
                else{
                    // Cautious mode
                    this._safeMode();
                }
            }
        },
        
        /*
        Method: startTick
            Calls <Moostick.initialize> on-demand, always in auto-start mode.
        
        Arguments:
         	lists        - See: <Moostick.list>
        	interval     - See: <Moostick.interval>
        	fxOptions    - See: <Moostick.fxOptions>
        	trust4Speed  - See: <Moostick.trust4Speed>
        	trustOpacity - See: <Moostick.trustOpacity>
        
        See also:
        	<Moostick.stopTick>, <Moostick.pauseTick>
        	and <Moostick.resumeTick>.
        */
        startTick: function(list, interval, fxOptions, trust4Speed, trustOpacity){
            this.initialize(list, true, interval, fxOptions, trust4Speed, trustOpacity);
        },
        
        /*
        Method: stopTick
            Stops the ticker completely, including the attached mouse events.
        
        See also:
        	<Moostick.startTick>.
        */
        stopTick: function(){
            // TODO: figure out why removeEvent isn't working here
            this.list.$events.mouseenter = false;
            this.list.$events.mouseleave = false;
            this.pauseTick();
        },
        
        /*
        Method: pauseTick
            Pauses the ticker.
        
        See also:
        	<Moostick.resumeTick> and <Moostick.stopTick>.
        */
        pauseTick: function(){
            this._elSched = $clear(this._elSched);
        },    
        
        /*
        Method: resumeTick
            Un-pauses the ticker.
        
        See also:
        	<Moostick.pauseTick> and <Moostick.stopTick>.
        */
        resumeTick: function(){
            this._schedule();
        },
        
        // Group: Private Methods
        
        /*
        Method: _fastMode
            AKA the Kamikaze mode. Bypasses all element validation.
        */
        _fastMode: function(){
            this._liHandler();
            this._schedule();
        },
        
        /*
        Method: _safeMode
            AKA the cautious mode. Validates the passed <Moostick.list> and
            tries to apply arbitray default styles before lauching the ticker.
        */
        _safeMode: function(){
     
            if(this.list){
     
                if($ES('li', this.list)){
                    var items = $ES('li', this.list);
                    // Assign proprietary CSS class if not already assigned
                    if(!this.list.hasClass('moostick')) this.list.addClass('moostick');
                   
                    // Test for an indication of existing style assignements,
                    var noStyle = false;
                    if( this.list.getStyle('overflow') != 'hidden'
                        || items[0].getStyle('display') != 'block'
                        || items[0].getStyle('list-style-type') != 'none'){
                        // we'll assume (!) no compatible style has been applied
                        noStyle = true;
                    }
                        
                    if(noStyle){                
                        this.list.setStyles({
                            'display' : 'block',
                            'height'  : '1.1em',
                            'margin'  : 0,
                            'padding' : '2px 0 2px 0',
                            'overflow': 'hidden'
                        });
                        
                        this._liHandler({
                            'display'        : 'block',
                            'list-style-type': 'none',
                            'margin'         : '0 auto 0 auto',
                            'padding'        : 0
                        });
                    }
                    else{
                        this._liHandler();
                    }
                    
                    // We should now have a *more* trustworthy environment
                    this._schedule();
                }
            }
        },
        
        /*
        Method: _setMouseEvents
            Sets the mouse events to pause the ticker when the mourse
            cursor enters the display area. Greatly helps with usability,
            while still remaining unobstrusive. Uses MooTools' mouseenter
            and mouseenter.
        */
        _setMouseEvents: function(){  
            this.list.addEvents({
                'mouseenter': function(){
                    this.moostick.pauseTick();
                },
                'mouseleave': function(){
                    this.moostick.resumeTick();
                }
            });
        },
        
        /*
        Method: _liHandler
        	Loops through the list's li elements to set the styles and opacity. 
        
        Arguments:
        	styles - See <http://docs.mootools.net/Native/Element.js#Element.setStyles>
        */
        _liHandler: function(styles){
            // Verify styles value and format
            if((!styles) || (typeof(styles) != 'object')) styles = false;
            
            // no need to even loop if no styles were sent and trusting opacity
            if((this.trustOpacity !== true) || (styles !== false)){
                
                $ES('li', this.list).each(function(li){
                    // Apply styles before forcing opacity
                    if(styles !== false) li.setStyles(styles);
                    
                    // Turn off all li but first
                    if(this.trustOpacity !== true)
                        li.setOpacity(0);
                });
            }
        },
        
        /*
        Method: _schedule
        	Prepares for and schedules <Moostick._run>.
        	Also sets up <Moostick._setMouseEvents>. 
        */
        _schedule: function(){
            if(!this._elSched){
                // Define if we should fade the first item in or not.
                var firstItem = $E('li', this.list);
                if(firstItem.getStyle('opacity') != 1)
                    this._fadeIn(firstItem);
                else if(this._firstRun === true)
                    this._fadeIn(firstItem);
                
                // Schedule <Moostick._run>
                this._elSched = this._run.periodical(
                    this.interval,
                    this,
                    this.list
                );
                
                // Set <Moostick._setMouseEvents>
                listEvents = this.list.$events;
                if(!listEvents)
                    this._setMouseEvents();
                else if((!listEvents.mouseenter) && (!listEvents.mouseleave))
                    this._setMouseEvents();
            }
        },
        
        /*
        Method: _run
        	Moostick transition: move first to last, fade new first in
        	then set opacity on new last to 0.
        */
        _run: function(){
            var items = $ES('li', this.list);
            
            // If we have at least 2
            if(items[1]){
                // Move first to last
                items[0].injectAfter(items.getLast());
                
                // Fade new first in
                this._fadeIn(items[1]);
                
                // Set new last off, for future fade in
                $ES('li', this.list).getLast().setOpacity(0);
            }
        },
        
        /*
        Method: _fadeIn
        	Handles the MooTools opacity effect, from 0 to 1
        
        Arguments:
        	item - li element to apply the effect to. 
        */
        _fadeIn: function(item){
            this._fx = item.effect('opacity', this.fxOptions).start(0,1);
            this._firstRun = false;
        }
    
    }); // end Moostick
    
}
