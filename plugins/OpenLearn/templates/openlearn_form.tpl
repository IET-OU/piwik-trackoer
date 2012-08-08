{assign var=showSitesSelection value=false}

{include file="CoreHome/templates/header.tpl"}

{* if isset($menu) && $menu}{include file="CoreHome/templates/menu.tpl"}{/if *}

<div class="page">
<div class="pageWrap">
	{*
	<div class="nav_sep"></div>
    <div class="top_controls">
        {include file="CoreHome/templates/period_select.tpl"}
        {include file="CoreHome/templates/header_message.tpl"}
	    {ajaxRequestErrorDiv}
    </div>
    
    {ajaxLoadingDiv}
    *}

    <div id="content" class="openlearn-form X-home">
        {* if $content}{$content}{/if *}

        <h2>OpenLearn License form</h2>
        
        <p>NOTE: not live - just a mockup at the moment!
        
        <form method="post" action="">
        <h3>Input</h3>
        <br />
        <p class=fitem>
        <label><input type=radio name=subsite value=learningspace /> LearningSpace</label>
        <label><input type=radio name=subsite value=labspace /> LabSpace</label>

        <br /><br />
        <p class=fitem>
        <label >Course module ID
        <input name=courseid placeholder="1234 | short_name" /></label>
        <p class=fhint>For example, '<a href="http://labspace.open.ac.uk/Learning_to_Learn_1.0">Learning_to_Learn_1.0</a>' (LabSpace)
        <p class=fhint>For example, '<a href="http://labspace.open.ac.uk/course/view.php?id=7442">7442</a>' (Labspace)


        <h3>Output</h3>
        <br /><br />
        <p id=cc_preview class=fitem>
        Here is a preview: <br /><br />
        {$cc_code}
        </p>
 
        <br /><br />
        <p class=fitem >
        <label >Copy this code to let your visitors know!<br />
        <textarea id=cc_code rows=12 cols=75 readonly
        >{$cc_code_escaped}</textarea>
        </label>
       

        </form>
    </div>
    <div class="clear"></div>
</div>
</div>

<br/><br/>
{include file="CoreHome/templates/piwik_tag.tpl"}
</div>
</body>
</html>
