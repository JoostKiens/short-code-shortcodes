short-code-shortcodes
=====================

WordPress plugin for shortcodes rendering &lt;code> and &lt;pre>&lt;code> with content converted to htmlentities &amp; disabling wptexturize.

Description
--------------------------------------

This WordPress plugin adds two shortcodes for rendering code:

-   [code] for inline code
-   [precode] for blocklevel code

These shortcodes solve 2 problems:

* Ocassional auto transformation to smart quotes. WP _should_ not convert quotes to smart quotes between [code]<pre>[/code] & [code]<code>[/code], but sometimes it happens anyway. These shortcodes explicitly don't use wptexturize.
* The need to manually convert html tags, etc to htmlentities. The shortcodes convert the content to htmlentities (no quotes, utf-8)

### Usage
`[code]<p>This is the "code" shortcode, for in-line code. Use it in the same way as you'd use <code>.[/code]`

    [precode]
    <p>
        This is the "precode" shortcode, for block-level code.
        Use it in the same way as you'd use <pre><code>.
    <p>
    [/precode]

### Shortcode attributes
There are no functional attributes. 

[Core HTML attributes](http://www.w3.org/wiki/HTML/Attributes/_Global#Core_Attributes) are allowed and get rendered in the resulting html. Example:
`[code class="code-html" lang="fr-FR"]<h1>Le monde est a nous</h1>[/code]`

### Installation
1. Upload short-code-shortcode folder to `wp-content/plugins/`
1. Activate plugin through the 'Plugins' menu in WordPress

## Change log
#### 0.1.1
Added trim function to the clean_code_content method

#### 0.1.0
Initial commit