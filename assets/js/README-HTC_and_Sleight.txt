*.htc, htcmime.php and sleight.js notes:
------------------------------------------------------------------------------

These .htc files enable IE versions 5.5 and 6 behave in a more standards compliant manner. 

There is a scaling option for the .png fixes as noted below:

crop: Clips the image to fit the dimensions of the object.

image: Default. Enlarges or reduces the border of the object to fit the dimensions of the image.

scale: Stretches or shrinks the image to fill the borders of the object.

In sleight.js, the sizing method is located near line 63.
In pngbehavior.htc it's located near line 65.

I suppose the pngbehavior method would be a bit faster or more efficient considering it bypasses the JS step, especially if used in an IE Conditional Comment like the following (this goes in the head section of your templates):

    <!--[if lt IE 7]>
        <style type="text/css">
            body { behavior: url(assets/js/htcmime.php?file=csshover.htc) }
            img { behavior: url(assets/js/htcmime.php?file=pngbehavior.htc) }
        </style>
    <![endif]-->

Also note that serving the htc files via teh technique above ensures that they get teh proper mime-type, which is important now that XP SP2 is prevalent. For more information, please see http://www.hoeben.net/node/83

If you use the .htaccess file provided with MODx, you should not need to use the htcmime.php helper file. In that case, your IE CC would look like:

    <!--[if lt IE 7]>
        <style type="text/css">
            body { behavior: url(assets/js/csshover.htc) }
            img { behavior: url(assets/js/pngbehavior.htc) }
        </style>
    <![endif]-->
