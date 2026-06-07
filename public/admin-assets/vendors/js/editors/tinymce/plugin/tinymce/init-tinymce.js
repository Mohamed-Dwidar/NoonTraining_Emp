tinymce.init({
    /* replace textarea having class .tinymce with tinymce editor */
    selector: "textarea.tinymce",
    forced_root_block : 'div',

    /* RTL defaults */
    directionality: 'rtl',
    content_style: 'body { direction: rtl; text-align: right; }',

    /* theme of the editor */
    theme: "modern",
    skin: "lightgray",

    /* width and height of the editor */
    width: "100%",
    height: 300,
    subfolder:"",

    /* display statusbar */
    statubar: true,
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern filemanager"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustifyt | bullist numlist outdent indent | ltr rtl | fontsizeselect hr",
    // toolbar2: "cut copy paste | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media",
    // toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | code preview | save ",

   image_advtab: true,
    /* style */
    style_formats: [
        {title: "Headers", items: [
                {title: "Header 1", format: "h1"},
                {title: "Header 2", format: "h2"},
                {title: "Header 3", format: "h3"},
                {title: "Header 4", format: "h4"},
                {title: "Header 5", format: "h5"},
                {title: "Header 6", format: "h6"}
            ]},
        {title: "Inline", items: [
                {title: "Bold", icon: "bold", format: "bold"},
                {title: "Italic", icon: "italic", format: "italic"},
                {title: "Underline", icon: "underline", format: "underline"},
                {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
                {title: "Superscript", icon: "superscript", format: "superscript"},
                {title: "Subscript", icon: "subscript", format: "subscript"},
                {title: "Code", icon: "code", format: "code"}
            ]},
        {title: "Blocks", items: [
                {title: "Paragraph", format: "p"},
                {title: "Blockquote", format: "blockquote"},
                {title: "Div", format: "div"},
                {title: "Pre", format: "pre"}
            ]},
        {title: "Alignment", items: [
                {title: "Left", icon: "alignleft", format: "alignleft"},
                {title: "Center", icon: "aligncenter", format: "aligncenter"},
                {title: "Right", icon: "alignright", format: "alignright"},
                {title: "Justify", icon: "alignjustify", format: "alignjustify"}
            ]}
    ]
});
