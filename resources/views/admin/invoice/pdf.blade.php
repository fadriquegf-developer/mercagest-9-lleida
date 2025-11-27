<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>Factura</title>


    <meta name="description" content="OneUI - Admin Dashboard Template & UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Stylesheets -->
    <!-- Web fonts -->

    <!-- OneUI CSS framework -->

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <!-- END Stylesheets -->

    <style type="text/css">
        html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        body {
            margin: 0;
        }
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        main,
        menu,
        nav,
        section,
        summary {
            display: block;
        }
        audio,
        canvas,
        progress,
        video {
            display: inline-block;
            vertical-align: baseline;
        }
        audio:not([controls]) {
            display: none;
            height: 0;
        }
        [hidden],
        template {
            display: none;
        }
        a {
            background-color: transparent;
        }
        a:active,
        a:hover {
            outline: 0;
        }
        abbr[title] {
            border-bottom: 1px dotted;
        }
        b,
        strong {
            font-weight: bold;
        }
        dfn {
            font-style: italic;
        }
        h1 {
            margin: .67em 0;
            font-size: 2em;
        }
        mark {
            color: #000;
            background: #ff0;
        }
        small {
            font-size: 80%;
        }
        sub,
        sup {
            position: relative;
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline;
        }
        sup {
            top: -0.5em;
        }
        sub {
            bottom: -0.25em;
        }
        img {
            border: 0;
        }
        svg:not(:root) {
            overflow: hidden;
        }
        figure {
            margin: 1em 40px;
        }
        hr {
            height: 0;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
        }
        pre {
            overflow: auto;
        }
        code,
        kbd,
        pre,
        samp {
            font-family: monospace, monospace;
            font-size: 1em;
        }
        button,
        input,
        optgroup,
        select,
        textarea {
            margin: 0;
            font: inherit;
            color: inherit;
        }
        button {
            overflow: visible;
        }
        button,
        select {
            text-transform: none;
        }
        button,
        html input[type="button"],
        input[type="reset"],
        input[type="submit"] {
            -webkit-appearance: button;
            cursor: pointer;
        }
        button[disabled],
        html input[disabled] {
            cursor: default;
        }
        button::-moz-focus-inner,
        input::-moz-focus-inner {
            padding: 0;
            border: 0;
        }
        input {
            line-height: normal;
        }
        input[type="checkbox"],
        input[type="radio"] {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 0;
        }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            height: auto;
        }
        input[type="search"] {
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            -webkit-appearance: textfield;
        }
        input[type="search"]::-webkit-search-cancel-button,
        input[type="search"]::-webkit-search-decoration {
            -webkit-appearance: none;
        }
        fieldset {
            padding: .35em .625em .75em;
            margin: 0 2px;
            border: 1px solid #c0c0c0;
        }
        legend {
            padding: 0;
            border: 0;
        }
        textarea {
            overflow: auto;
        }
        optgroup {
            font-weight: bold;
        }

        /*! Source: https://github.com/h5bp/html5-boilerplate/blob/master/src/css/main.css */
        @media print {
            *,
            *:before,
            *:after {
                color: #000 !important;
                text-shadow: none !important;
                background: transparent !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }
            a,
            a:visited {
                text-decoration: underline;
            }
            a[href]:after {
                content: " (" attr(href) ")";
            }
            abbr[title]:after {
                content: " (" attr(title) ")";
            }
            a[href^="#"]:after,
            a[href^="javascript:"]:after {
                content: "";
            }
            pre,
            blockquote {
                border: 1px solid #999;
                page-break-inside: avoid;
            }

            tr,
            img {
                page-break-inside: avoid;
            }
            img {
                max-width: 100% !important;
            }
            p,
            h2,
            h3 {
                orphans: 3;
                widows: 3;
            }
            h2,
            h3 {
                page-break-after: avoid;
            }
            select {
                background: #fff !important;
            }
            .navbar {
                display: none;
            }
            .btn > .caret,
            .dropup > .btn > .caret {
                border-top-color: #000 !important;
            }
            .label {
                border: 1px solid #000;
            }

        }
        @font-face {

        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        *:before,
        *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        html {
            font-size: 10px;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
        }
        input,
        button,
        select,
        textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }
        a {
            color: #337ab7;
            text-decoration: none;
        }
        a:hover,
        a:focus {
            color: #23527c;
            text-decoration: underline;
        }
        a:focus {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px;
        }
        figure {
            margin: 0;
        }
        img {
            vertical-align: middle;
        }
        .img-responsive,
        .thumbnail > img,
        .thumbnail a > img,
        .carousel-inner > .item > img,
        .carousel-inner > .item > a > img {
            display: block;
            max-width: 100%;
            height: auto;
        }
        .img-rounded {
            border-radius: 6px;
        }
        .img-thumbnail {
            display: inline-block;
            max-width: 100%;
            height: auto;
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all 0.2s ease-in-out;
            -o-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
        }
        .img-circle {
            border-radius: 50%;
        }
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
        .sr-only-focusable:active,
        .sr-only-focusable:focus {
            position: static;
            width: auto;
            height: auto;
            margin: 0;
            overflow: visible;
            clip: auto;
        }
        [role="button"] {
            cursor: pointer;
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
        }
        h1 small,
        h2 small,
        h3 small,
        h4 small,
        h5 small,
        h6 small,
        .h1 small,
        .h2 small,
        .h3 small,
        .h4 small,
        .h5 small,
        .h6 small,
        h1 .small,
        h2 .small,
        h3 .small,
        h4 .small,
        h5 .small,
        h6 .small,
        .h1 .small,
        .h2 .small,
        .h3 .small,
        .h4 .small,
        .h5 .small,
        .h6 .small {
            font-weight: normal;
            line-height: 1;
            color: #777;
        }
        h1,
        .h1,
        h2,
        .h2,
        h3,
        .h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        h1 small,
        .h1 small,
        h2 small,
        .h2 small,
        h3 small,
        .h3 small,
        h1 .small,
        .h1 .small,
        h2 .small,
        .h2 .small,
        h3 .small,
        .h3 .small {
            font-size: 65%;
        }
        h4,
        .h4,
        h5,
        .h5,
        h6,
        .h6 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        h4 small,
        .h4 small,
        h5 small,
        .h5 small,
        h6 small,
        .h6 small,
        h4 .small,
        .h4 .small,
        h5 .small,
        .h5 .small,
        h6 .small,
        .h6 .small {
            font-size: 75%;
        }
        h1,
        .h1 {
            font-size: 36px;
        }
        h2,
        .h2 {
            font-size: 30px;
        }
        h3,
        .h3 {
            font-size: 24px;
        }
        h4,
        .h4 {
            font-size: 18px;
        }
        h5,
        .h5 {
            font-size: 14px;
        }
        h6,
        .h6 {
            font-size: 12px;
        }
        p {
            margin: 0 0 10px;
        }
        .lead {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;
        }
        @media (min-width: 768px) {
            .lead {
                font-size: 21px;
            }
        }
        small,
        .small {
            font-size: 85%;
        }
        mark,
        .mark {
            padding: .2em;
            background-color: #fcf8e3;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .text-nowrap {
            white-space: nowrap;
        }
        .text-lowercase {
            text-transform: lowercase;
        }
        .text-uppercase {
            text-transform: uppercase;
        }
        .text-capitalize {
            text-transform: capitalize;
        }
        .text-muted {
            color: #777;
        }
        .text-primary {
            color: #337ab7;
        }
        .page-header {
            padding-bottom: 9px;
            margin: 40px 0 20px;
            border-bottom: 1px solid #eee;
        }
        ul,
        ol {
            margin-top: 0;
            margin-bottom: 10px;
        }
        ul ul,
        ol ul,
        ul ol,
        ol ol {
            margin-bottom: 0;
        }
        .list-unstyled {
            padding-left: 0;
            list-style: none;
        }
        .list-inline {
            padding-left: 0;
            margin-left: -5px;
            list-style: none;
        }
        .list-inline > li {
            display: inline-block;
            padding-right: 5px;
            padding-left: 5px;
        }
        dl {
            margin-top: 0;
            margin-bottom: 20px;
        }
        dt,
        dd {
            line-height: 1.42857143;
        }
        dt {
            font-weight: bold;
        }
        dd {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            .dl-horizontal dt {
                float: left;
                width: 160px;
                overflow: hidden;
                clear: left;
                text-align: right;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .dl-horizontal dd {
                margin-left: 180px;
            }
        }
        abbr[title],
        abbr[data-original-title] {
            cursor: help;
            border-bottom: 1px dotted #777;
        }
        .initialism {
            font-size: 90%;
            text-transform: uppercase;
        }
        blockquote {
            padding: 10px 20px;
            margin: 0 0 20px;
            font-size: 17.5px;
            border-left: 5px solid #eee;
        }
        blockquote p:last-child,
        blockquote ul:last-child,
        blockquote ol:last-child {
            margin-bottom: 0;
        }
        blockquote footer,
        blockquote small,
        blockquote .small {
            display: block;
            font-size: 80%;
            line-height: 1.42857143;
            color: #777;
        }
        blockquote footer:before,
        blockquote small:before,
        blockquote .small:before {
            content: '\2014 \00A0';
        }
        .blockquote-reverse,
        blockquote.pull-right {
            padding-right: 15px;
            padding-left: 0;
            text-align: right;
            border-right: 5px solid #eee;
            border-left: 0;
        }
        .blockquote-reverse footer:before,
        blockquote.pull-right footer:before,
        .blockquote-reverse small:before,
        blockquote.pull-right small:before,
        .blockquote-reverse .small:before,
        blockquote.pull-right .small:before {
            content: '';
        }
        .blockquote-reverse footer:after,
        blockquote.pull-right footer:after,
        .blockquote-reverse small:after,
        blockquote.pull-right small:after,
        .blockquote-reverse .small:after,
        blockquote.pull-right .small:after {
            content: '\00A0 \2014';
        }
        address {
            margin-bottom: 20px;
            font-style: normal;
            line-height: 1.42857143;
        }
        code,
        kbd,
        pre,
        samp {
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
        }
        code {
            padding: 2px 4px;
            font-size: 90%;
            color: #c7254e;
            background-color: #f9f2f4;
            border-radius: 4px;
        }
        kbd {
            padding: 2px 4px;
            font-size: 90%;
            color: #fff;
            background-color: #333;
            border-radius: 3px;
            -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.25);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.25);
        }
        kbd kbd {
            padding: 0;
            font-size: 100%;
            font-weight: bold;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        pre code {
            padding: 0;
            font-size: inherit;
            color: inherit;
            white-space: pre-wrap;
            background-color: transparent;
            border-radius: 0;
        }
        .pre-scrollable {
            max-height: 340px;
            overflow-y: scroll;
        }
        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        @media (min-width: 768px) {
            .container {
                width: 750px;
            }
        }
        @media (min-width: 992px) {
            .container {
                width: 970px;
            }
        }
        @media (min-width: 1200px) {
            .container {
                width: 1170px;
            }
        }
        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-xs-1,
        .col-sm-1,
        .col-md-1,
        .col-lg-1,
        .col-xs-2,
        .col-sm-2,
        .col-md-2,
        .col-lg-2,
        .col-xs-3,
        .col-sm-3,
        .col-md-3,
        .col-lg-3,
        .col-xs-4,
        .col-sm-4,
        .col-md-4,
        .col-lg-4,
        .col-xs-5,
        .col-sm-5,
        .col-md-5,
        .col-lg-5,
        .col-xs-6,
        .col-sm-6,
        .col-md-6,
        .col-lg-6,
        .col-xs-7,
        .col-sm-7,
        .col-md-7,
        .col-lg-7,
        .col-xs-8,
        .col-sm-8,
        .col-md-8,
        .col-lg-8,
        .col-xs-9,
        .col-sm-9,
        .col-md-9,
        .col-lg-9,
        .col-xs-10,
        .col-sm-10,
        .col-md-10,
        .col-lg-10,
        .col-xs-11,
        .col-sm-11,
        .col-md-11,
        .col-lg-11,
        .col-xs-12,
        .col-sm-12,
        .col-md-12,
        .col-lg-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .col-xs-1,
        .col-xs-2,
        .col-xs-3,
        .col-xs-4,
        .col-xs-5,
        .col-xs-6,
        .col-xs-7,
        .col-xs-8,
        .col-xs-9,
        .col-xs-10,
        .col-xs-11,
        .col-xs-12 {
            float: left;
        }
        .col-xs-12 {
            width: 100%;
        }
        .col-xs-11 {
            width: 91.66666667%;
        }
        .col-xs-10 {
            width: 83.33333333%;
        }
        .col-xs-9 {
            width: 75%;
        }
        .col-xs-8 {
            width: 66.66666667%;
        }
        .col-xs-7 {
            width: 58.33333333%;
        }
        .col-xs-6 {
            width: 50%;
        }
        .col-xs-5 {
            width: 41.66666667%;
        }
        .col-xs-4 {
            width: 33.33333333%;
        }
        .col-xs-3 {
            width: 25%;
        }
        .col-xs-2 {
            width: 16.66666667%;
        }
        .col-xs-1 {
            width: 8.33333333%;
        }
        .col-xs-pull-12 {
            right: 100%;
        }
        .col-xs-pull-11 {
            right: 91.66666667%;
        }
        .col-xs-pull-10 {
            right: 83.33333333%;
        }
        .col-xs-pull-9 {
            right: 75%;
        }
        .col-xs-pull-8 {
            right: 66.66666667%;
        }
        .col-xs-pull-7 {
            right: 58.33333333%;
        }
        .col-xs-pull-6 {
            right: 50%;
        }
        .col-xs-pull-5 {
            right: 41.66666667%;
        }
        .col-xs-pull-4 {
            right: 33.33333333%;
        }
        .col-xs-pull-3 {
            right: 25%;
        }
        .col-xs-pull-2 {
            right: 16.66666667%;
        }
        .col-xs-pull-1 {
            right: 8.33333333%;
        }
        .col-xs-pull-0 {
            right: auto;
        }
        .col-xs-push-12 {
            left: 100%;
        }
        .col-xs-push-11 {
            left: 91.66666667%;
        }
        .col-xs-push-10 {
            left: 83.33333333%;
        }
        .col-xs-push-9 {
            left: 75%;
        }
        .col-xs-push-8 {
            left: 66.66666667%;
        }
        .col-xs-push-7 {
            left: 58.33333333%;
        }
        .col-xs-push-6 {
            left: 50%;
        }
        .col-xs-push-5 {
            left: 41.66666667%;
        }
        .col-xs-push-4 {
            left: 33.33333333%;
        }
        .col-xs-push-3 {
            left: 25%;
        }
        .col-xs-push-2 {
            left: 16.66666667%;
        }
        .col-xs-push-1 {
            left: 8.33333333%;
        }
        .col-xs-push-0 {
            left: auto;
        }
        .col-xs-offset-12 {
            margin-left: 100%;
        }
        .col-xs-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-xs-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-xs-offset-9 {
            margin-left: 75%;
        }
        .col-xs-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-xs-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-xs-offset-6 {
            margin-left: 50%;
        }
        .col-xs-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-xs-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-xs-offset-3 {
            margin-left: 25%;
        }
        .col-xs-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-xs-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-xs-offset-0 {
            margin-left: 0;
        }
        @media (min-width: 768px) {
            .col-sm-1,
            .col-sm-2,
            .col-sm-3,
            .col-sm-4,
            .col-sm-5,
            .col-sm-6,
            .col-sm-7,
            .col-sm-8,
            .col-sm-9,
            .col-sm-10,
            .col-sm-11,
            .col-sm-12 {
                float: left;
            }
            .col-sm-12 {
                width: 100%;
            }
            .col-sm-11 {
                width: 91.66666667%;
            }
            .col-sm-10 {
                width: 83.33333333%;
            }
            .col-sm-9 {
                width: 75%;
            }
            .col-sm-8 {
                width: 66.66666667%;
            }
            .col-sm-7 {
                width: 58.33333333%;
            }
            .col-sm-6 {
                width: 50%;
            }
            .col-sm-5 {
                width: 41.66666667%;
            }
            .col-sm-4 {
                width: 33.33333333%;
            }
            .col-sm-3 {
                width: 25%;
            }
            .col-sm-2 {
                width: 16.66666667%;
            }
            .col-sm-1 {
                width: 8.33333333%;
            }
            .col-sm-pull-12 {
                right: 100%;
            }
            .col-sm-pull-11 {
                right: 91.66666667%;
            }
            .col-sm-pull-10 {
                right: 83.33333333%;
            }
            .col-sm-pull-9 {
                right: 75%;
            }
            .col-sm-pull-8 {
                right: 66.66666667%;
            }
            .col-sm-pull-7 {
                right: 58.33333333%;
            }
            .col-sm-pull-6 {
                right: 50%;
            }
            .col-sm-pull-5 {
                right: 41.66666667%;
            }
            .col-sm-pull-4 {
                right: 33.33333333%;
            }
            .col-sm-pull-3 {
                right: 25%;
            }
            .col-sm-pull-2 {
                right: 16.66666667%;
            }
            .col-sm-pull-1 {
                right: 8.33333333%;
            }
            .col-sm-pull-0 {
                right: auto;
            }
            .col-sm-push-12 {
                left: 100%;
            }
            .col-sm-push-11 {
                left: 91.66666667%;
            }
            .col-sm-push-10 {
                left: 83.33333333%;
            }
            .col-sm-push-9 {
                left: 75%;
            }
            .col-sm-push-8 {
                left: 66.66666667%;
            }
            .col-sm-push-7 {
                left: 58.33333333%;
            }
            .col-sm-push-6 {
                left: 50%;
            }
            .col-sm-push-5 {
                left: 41.66666667%;
            }
            .col-sm-push-4 {
                left: 33.33333333%;
            }
            .col-sm-push-3 {
                left: 25%;
            }
            .col-sm-push-2 {
                left: 16.66666667%;
            }
            .col-sm-push-1 {
                left: 8.33333333%;
            }
            .col-sm-push-0 {
                left: auto;
            }
            .col-sm-offset-12 {
                margin-left: 100%;
            }
            .col-sm-offset-11 {
                margin-left: 91.66666667%;
            }
            .col-sm-offset-10 {
                margin-left: 83.33333333%;
            }
            .col-sm-offset-9 {
                margin-left: 75%;
            }
            .col-sm-offset-8 {
                margin-left: 66.66666667%;
            }
            .col-sm-offset-7 {
                margin-left: 58.33333333%;
            }
            .col-sm-offset-6 {
                margin-left: 50%;
            }
            .col-sm-offset-5 {
                margin-left: 41.66666667%;
            }
            .col-sm-offset-4 {
                margin-left: 33.33333333%;
            }
            .col-sm-offset-3 {
                margin-left: 25%;
            }
            .col-sm-offset-2 {
                margin-left: 16.66666667%;
            }
            .col-sm-offset-1 {
                margin-left: 8.33333333%;
            }
            .col-sm-offset-0 {
                margin-left: 0;
            }
        }
        @media (min-width: 992px) {
            .col-md-1,
            .col-md-2,
            .col-md-3,
            .col-md-4,
            .col-md-5,
            .col-md-6,
            .col-md-7,
            .col-md-8,
            .col-md-9,
            .col-md-10,
            .col-md-11,
            .col-md-12 {
                float: left;
            }
            .col-md-12 {
                width: 100%;
            }
            .col-md-11 {
                width: 91.66666667%;
            }
            .col-md-10 {
                width: 83.33333333%;
            }
            .col-md-9 {
                width: 75%;
            }
            .col-md-8 {
                width: 66.66666667%;
            }
            .col-md-7 {
                width: 58.33333333%;
            }
            .col-md-6 {
                width: 50%;
            }
            .col-md-5 {
                width: 41.66666667%;
            }
            .col-md-4 {
                width: 33.33333333%;
            }
            .col-md-3 {
                width: 25%;
            }
            .col-md-2 {
                width: 16.66666667%;
            }
            .col-md-1 {
                width: 8.33333333%;
            }
            .col-md-pull-12 {
                right: 100%;
            }
            .col-md-pull-11 {
                right: 91.66666667%;
            }
            .col-md-pull-10 {
                right: 83.33333333%;
            }
            .col-md-pull-9 {
                right: 75%;
            }
            .col-md-pull-8 {
                right: 66.66666667%;
            }
            .col-md-pull-7 {
                right: 58.33333333%;
            }
            .col-md-pull-6 {
                right: 50%;
            }
            .col-md-pull-5 {
                right: 41.66666667%;
            }
            .col-md-pull-4 {
                right: 33.33333333%;
            }
            .col-md-pull-3 {
                right: 25%;
            }
            .col-md-pull-2 {
                right: 16.66666667%;
            }
            .col-md-pull-1 {
                right: 8.33333333%;
            }
            .col-md-pull-0 {
                right: auto;
            }
            .col-md-push-12 {
                left: 100%;
            }
            .col-md-push-11 {
                left: 91.66666667%;
            }
            .col-md-push-10 {
                left: 83.33333333%;
            }
            .col-md-push-9 {
                left: 75%;
            }
            .col-md-push-8 {
                left: 66.66666667%;
            }
            .col-md-push-7 {
                left: 58.33333333%;
            }
            .col-md-push-6 {
                left: 50%;
            }
            .col-md-push-5 {
                left: 41.66666667%;
            }
            .col-md-push-4 {
                left: 33.33333333%;
            }
            .col-md-push-3 {
                left: 25%;
            }
            .col-md-push-2 {
                left: 16.66666667%;
            }
            .col-md-push-1 {
                left: 8.33333333%;
            }
            .col-md-push-0 {
                left: auto;
            }
            .col-md-offset-12 {
                margin-left: 100%;
            }
            .col-md-offset-11 {
                margin-left: 91.66666667%;
            }
            .col-md-offset-10 {
                margin-left: 83.33333333%;
            }
            .col-md-offset-9 {
                margin-left: 75%;
            }
            .col-md-offset-8 {
                margin-left: 66.66666667%;
            }
            .col-md-offset-7 {
                margin-left: 58.33333333%;
            }
            .col-md-offset-6 {
                margin-left: 50%;
            }
            .col-md-offset-5 {
                margin-left: 41.66666667%;
            }
            .col-md-offset-4 {
                margin-left: 33.33333333%;
            }
            .col-md-offset-3 {
                margin-left: 25%;
            }
            .col-md-offset-2 {
                margin-left: 16.66666667%;
            }
            .col-md-offset-1 {
                margin-left: 8.33333333%;
            }
            .col-md-offset-0 {
                margin-left: 0;
            }
        }
        @media (min-width: 1200px) {
            .col-lg-1,
            .col-lg-2,
            .col-lg-3,
            .col-lg-4,
            .col-lg-5,
            .col-lg-6,
            .col-lg-7,
            .col-lg-8,
            .col-lg-9,
            .col-lg-10,
            .col-lg-11,
            .col-lg-12 {
                float: left;
            }
            .col-lg-12 {
                width: 100%;
            }
            .col-lg-11 {
                width: 91.66666667%;
            }
            .col-lg-10 {
                width: 83.33333333%;
            }
            .col-lg-9 {
                width: 75%;
            }
            .col-lg-8 {
                width: 66.66666667%;
            }
            .col-lg-7 {
                width: 58.33333333%;
            }
            .col-lg-6 {
                width: 50%;
            }
            .col-lg-5 {
                width: 41.66666667%;
            }
            .col-lg-4 {
                width: 33.33333333%;
            }
            .col-lg-3 {
                width: 25%;
            }
            .col-lg-2 {
                width: 16.66666667%;
            }
            .col-lg-1 {
                width: 8.33333333%;
            }
            .col-lg-pull-12 {
                right: 100%;
            }
            .col-lg-pull-11 {
                right: 91.66666667%;
            }
            .col-lg-pull-10 {
                right: 83.33333333%;
            }
            .col-lg-pull-9 {
                right: 75%;
            }
            .col-lg-pull-8 {
                right: 66.66666667%;
            }
            .col-lg-pull-7 {
                right: 58.33333333%;
            }
            .col-lg-pull-6 {
                right: 50%;
            }
            .col-lg-pull-5 {
                right: 41.66666667%;
            }
            .col-lg-pull-4 {
                right: 33.33333333%;
            }
            .col-lg-pull-3 {
                right: 25%;
            }
            .col-lg-pull-2 {
                right: 16.66666667%;
            }
            .col-lg-pull-1 {
                right: 8.33333333%;
            }
            .col-lg-pull-0 {
                right: auto;
            }
            .col-lg-push-12 {
                left: 100%;
            }
            .col-lg-push-11 {
                left: 91.66666667%;
            }
            .col-lg-push-10 {
                left: 83.33333333%;
            }
            .col-lg-push-9 {
                left: 75%;
            }
            .col-lg-push-8 {
                left: 66.66666667%;
            }
            .col-lg-push-7 {
                left: 58.33333333%;
            }
            .col-lg-push-6 {
                left: 50%;
            }
            .col-lg-push-5 {
                left: 41.66666667%;
            }
            .col-lg-push-4 {
                left: 33.33333333%;
            }
            .col-lg-push-3 {
                left: 25%;
            }
            .col-lg-push-2 {
                left: 16.66666667%;
            }
            .col-lg-push-1 {
                left: 8.33333333%;
            }
            .col-lg-push-0 {
                left: auto;
            }
            .col-lg-offset-12 {
                margin-left: 100%;
            }
            .col-lg-offset-11 {
                margin-left: 91.66666667%;
            }
            .col-lg-offset-10 {
                margin-left: 83.33333333%;
            }
            .col-lg-offset-9 {
                margin-left: 75%;
            }
            .col-lg-offset-8 {
                margin-left: 66.66666667%;
            }
            .col-lg-offset-7 {
                margin-left: 58.33333333%;
            }
            .col-lg-offset-6 {
                margin-left: 50%;
            }
            .col-lg-offset-5 {
                margin-left: 41.66666667%;
            }
            .col-lg-offset-4 {
                margin-left: 33.33333333%;
            }
            .col-lg-offset-3 {
                margin-left: 25%;
            }
            .col-lg-offset-2 {
                margin-left: 16.66666667%;
            }
            .col-lg-offset-1 {
                margin-left: 8.33333333%;
            }
            .col-lg-offset-0 {
                margin-left: 0;
            }
        }

        .clearfix:before,
        .clearfix:after,
        .dl-horizontal dd:before,
        .dl-horizontal dd:after,
        .container:before,
        .container:after,
        .container-fluid:before,
        .container-fluid:after,
        .row:before,
        .row:after,
        .form-horizontal .form-group:before,
        .form-horizontal .form-group:after,
        .btn-toolbar:before,
        .btn-toolbar:after,
        .btn-group-vertical > .btn-group:before,
        .btn-group-vertical > .btn-group:after,
        .nav:before,
        .nav:after,
        .navbar:before,
        .navbar:after,
        .navbar-header:before,
        .navbar-header:after,
        .navbar-collapse:before,
        .navbar-collapse:after,
        .pager:before,
        .pager:after,
        .panel-body:before,
        .panel-body:after,
        .modal-footer:before,
        .modal-footer:after {
            display: table;
            content: " ";
        }
        .clearfix:after,
        .dl-horizontal dd:after,
        .container:after,
        .container-fluid:after,
        .row:after,
        .form-horizontal .form-group:after,
        .btn-toolbar:after,
        .btn-group-vertical > .btn-group:after,
        .nav:after,
        .navbar:after,
        .navbar-header:after,
        .navbar-collapse:after,
        .pager:after,
        .panel-body:after,
        .modal-footer:after {
            clear: both;
        }
        .center-block {
            display: block;
            margin-right: auto;
            margin-left: auto;
        }
        .pull-right {
            float: right !important;
        }
        .pull-left {
            float: left !important;
        }
        .hide {
            display: none !important;
        }
        .show {
            display: block !important;
        }
        .invisible {
            visibility: hidden;
        }
        .text-hide {
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }
        .hidden {
            display: none !important;
        }
        .affix {
            position: fixed;
        }
        @-ms-viewport {
            width: device-width;
        }
        .visible-xs,
        .visible-sm,
        .visible-md,
        .visible-lg {
            display: none !important;
        }
        .visible-xs-block,
        .visible-xs-inline,
        .visible-xs-inline-block,
        .visible-sm-block,
        .visible-sm-inline,
        .visible-sm-inline-block,
        .visible-md-block,
        .visible-md-inline,
        .visible-md-inline-block,
        .visible-lg-block,
        .visible-lg-inline,
        .visible-lg-inline-block {
            display: none !important;
        }

        /*# sourceMappingURL=bootstrap.css.map */
        html,
        body {
            height: 100%;
        }
        body {
            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #646464;
        }
        .no-focus *:focus {
            outline: 0 !important;
        }
        a {
            color: #5c90d2;
            -webkit-transition: color 0.12s ease-out;
            transition: color 0.12s ease-out;
        }
        a.link-effect {
            position: relative;
        }
        a.link-effect:before {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            content: "";
            background-color: #3169b1;
            visibility: hidden;
            -webkit-transform: scaleX(0);
            -ms-transform: scaleX(0);
            transform: scaleX(0);
            -webkit-transition: -webkit-transform 0.12s ease-out;
            transition: transform 0.12s ease-out;
        }
        a:hover,
        a:focus {
            color: #3169b1;
            text-decoration: none;
        }
        a:hover.link-effect:before,
        a:focus.link-effect:before {
            visibility: visible;
            -webkit-transform: scaleX(1);
            -ms-transform: scaleX(1);
            transform: scaleX(1);
        }
        a:active {
            color: #5c90d2;
        }
        a.inactive {
            cursor: not-allowed;
        }
        a.inactive:focus {
            background-color: transparent !important;
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            margin: 0;
            font-family: "Source Sans Pro", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 600;
            line-height: 1.2;
            color: inherit;
        }
        h1 small,
        h2 small,
        h3 small,
        h4 small,
        h5 small,
        h6 small,
        .h1 small,
        .h2 small,
        .h3 small,
        .h4 small,
        .h5 small,
        .h6 small,
        h1 .small,
        h2 .small,
        h3 .small,
        h4 .small,
        h5 .small,
        h6 .small,
        .h1 .small,
        .h2 .small,
        .h3 .small,
        .h4 .small,
        .h5 .small,
        .h6 .small {
            font-weight: 600;
            font-size: 85%;
            color: #777;
        }
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-weight: inherit;
        }
        h1,
        .h1 {
            font-size: 36px;
        }
        h2,
        .h2 {
            font-size: 30px;
        }
        h3,
        .h3 {
            font-size: 24px;
        }
        h4,
        .h4 {
            font-size: 20px;
        }
        h5,
        .h5 {
            font-size: 16px;
        }
        h6,
        .h6 {
            font-size: 14px;
        }
        .page-heading {
            color: #545454;
            font-size: 28px;
            font-weight: 400;
        }
        .page-heading small {
            margin-top: 5px;
            display: block;
            color: #777;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;
        }

        .
        .css-input {
            position: relative;
            display: inline-block;
            margin: 2px 0;
            font-weight: 400;
            cursor: pointer;
        }
        .css-input input {
            position: absolute;
            opacity: 0;
        }
        .css-input input:focus + span {
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.25);
        }
        .css-input input + span {
            position: relative;
            display: inline-block;
            margin-top: -2px;
            margin-right: 3px;
            vertical-align: middle;
        }
        .css-input input + span:after {
            position: absolute;
            content: "";
        }
        .css-checkbox {
            margin: 7px 0;
        }
        .css-checkbox input + span {
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-transition: background-color 0.2s;
            transition: background-color 0.2s;
        }
        .css-checkbox input + span:after {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            font-family: "FontAwesome";
            font-size: 10px;
            color: #fff;
            line-height: 18px;
            content: "\f00c";
            text-align: center;
        }
        .css-checkbox:hover input + span {
            border-color: #ccc;
        }
        .css-checkbox.css-checkbox-sm {
            margin: 9px 0 8px;
            font-size: 12px;
        }
        .css-checkbox.css-checkbox-sm input + span {
            width: 16px;
            height: 16px;
        }
        .css-checkbox.css-checkbox-sm input + span:after {
            font-size: 8px;
            line-height: 15px;
        }
        .css-checkbox.css-checkbox-lg {
            margin: 3px 0;
        }
        .css-checkbox.css-checkbox-lg input + span {
            width: 30px;
            height: 30px;
        }
        .css-checkbox.css-checkbox-lg input + span:after {
            font-size: 12px;
            line-height: 30px;
        }
        .css-checkbox.css-checkbox-rounded input + span {
            border-radius: 3px;
        }
        .css-checkbox-default input:checked + span {
            background-color: #999999;
            border-color: #999999;
        }
        .css-checkbox-primary input:checked + span {
            background-color: #5c90d2;
            border-color: #5c90d2;
        }
        .css-checkbox-info input:checked + span {
            background-color: #70b9eb;
            border-color: #70b9eb;
        }
        .css-checkbox-success input:checked + span {
            background-color: #46c37b;
            border-color: #46c37b;
        }
        .css-checkbox-warning input:checked + span {
            background-color: #f3b760;
            border-color: #f3b760;
        }
        .css-checkbox-danger input:checked + span {
            background-color: #d26a5c;
            border-color: #d26a5c;
        }
        .css-radio {
            margin: 7px 0;
        }
        .css-radio input + span {
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
        }
        .css-radio input + span:after {
            top: 2px;
            right: 2px;
            bottom: 2px;
            left: 2px;
            background-color: #fff;
            border-radius: 50%;
            opacity: 0;
            -webkit-transition: opacity 0.2s ease-out;
            transition: opacity 0.2s ease-out;
        }
        .css-radio input:checked + span:after {
            opacity: 1;
        }
        .css-radio:hover input + span {
            border-color: #ccc;
        }
        .css-radio.css-radio-sm {
            margin: 9px 0 8px;
            font-size: 12px;
        }
        .css-radio.css-radio-sm input + span {
            width: 16px;
            height: 16px;
        }
        .css-radio.css-radio-lg {
            margin: 5px 0;
        }
        .css-radio.css-radio-lg input + span {
            width: 26px;
            height: 26px;
        }
        .css-radio-default input:checked + span:after {
            background-color: #999999;
        }
        .css-radio-primary input:checked + span:after {
            background-color: #5c90d2;
        }
        .css-radio-info input:checked + span:after {
            background-color: #70b9eb;
        }
        .css-radio-success input:checked + span:after {
            background-color: #46c37b;
        }
        .css-radio-warning input:checked + span:after {
            background-color: #f3b760;
        }
        .css-radio-danger input:checked + span:after {
            background-color: #d26a5c;
        }

        .block {
            margin-bottom: 30px;
            background-color: #fff;
            -webkit-box-shadow: 0 2px rgba(0, 0, 0, 0.01);
            box-shadow: 0 2px rgba(0, 0, 0, 0.01);
        }
        .side-content .block {
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .block-header {
            padding: 15px 20px;
            -webkit-transition: opacity 0.2s ease-out;
            transition: opacity 0.2s ease-out;
        }
        .block-header:before,
        .block-header:after {
            content: " ";
            display: table;
        }
        .block-header:after {
            clear: both;
        }
        .block-title {
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .block-title.text-normal {
            text-transform: none;
        }
        .block-title small {
            font-size: 13px;
            font-weight: normal;
            text-transform: none;
        }
        .block-content {
            margin: 0 auto;
            padding: 20px 20px 1px;
            max-width: 100%;
            overflow-x: visible;
            -webkit-transition: opacity 0.2s ease-out;
            transition: opacity 0.2s ease-out;
        }
        .block-content p,
        .block-content .push,
        .block-content .block,
        .block-content .items-push > div {
            margin-bottom: 20px;
        }
        .block-content .items-push-2x > div {
            margin-bottom: 40px;
        }
        .block-content .items-push-3x > div {
            margin-bottom: 60px;
        }
        .block-content.block-content-full {
            padding-bottom: 20px;
        }
        .block-content.block-content-full .pull-b {
            margin-bottom: -20px;
        }
        .block-content .pull-t {
            margin-top: -20px;
        }
        .block-content .pull-r-l {
            margin-right: -20px;
            margin-left: -20px;
        }
        .block-content .pull-b {
            margin-bottom: -1px;
        }
        .block-content.block-content-mini {
            padding-top: 10px;
        }
        .block-content.block-content-mini.block-content-full.block-content-mini {
            padding-bottom: 10px;
        }
        @media screen and (min-width: 1200px) {
            .block-content.block-content-narrow {
                padding-left: 10%;
                padding-right: 10%;
            }
        }
        .block.block-full .block-content {
            padding-bottom: 20px;
        }
        .block.block-full .block-content.block-content-mini {
            padding-bottom: 10px;
        }
        .block.block-bordered {
            border: 1px solid #e9e9e9;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .block.block-bordered .block-header {
            border-bottom: 1px solid #e9e9e9;
        }
        .block.block-rounded {
            border-radius: 4px;
        }
        .block.block-rounded .block-header {
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }
        .block.block-rounded .block-content:first-child {
            border-top-right-radius: 3px;
            border-top-left-radius: 3px;
        }
        .block.block-rounded .block-content:last-child {
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
        }
        .block.block-themed > .block-header {
            border-bottom: none;
        }
        .block.block-themed > .block-header .block-title {
            color: #fff;
        }
        .block.block-themed > .block-header .block-title small {
            color: rgba(255, 255, 255, 0.75);
        }
        .block.block-transparent {
            background-color: transparent;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .block.block-opt-refresh {
            position: relative;
        }
        .block.block-opt-refresh .block-header {
            opacity: .25;
        }
        .block.block-opt-refresh .block-content {
            opacity: .15;
        }
        .block.block-opt-refresh:after {
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -20px 0 0 -20px;
            width: 40px;
            height: 40px;
            line-height: 40px;
            color: #646464;
            font-family: Simple-Line-Icons;
            font-size: 18px;
            text-align: center;
            z-index: 2;
            content: "\e09a";
            -webkit-animation: fa-spin 2s infinite linear;
            animation: fa-spin 2s infinite linear;
        }
        .ie9 .block.block-opt-refresh:after {
            content: "Loading..";
        }
        .block.block-opt-fullscreen {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            margin-bottom: 0;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }
        .block.block-opt-hidden.block-bordered .block-header {
            border-bottom: none;
        }
        .block.block-opt-hidden .block-content {
            display: none;
        }
        a.block {
            display: block;
            color: #646464;
            font-weight: normal;
            -webkit-transition: all 0.15s ease-out;
            transition: all 0.15s ease-out;
        }
        a.block:hover {
            color: #646464;
            opacity: .9;
        }
        a.block.block-link-hover1:hover {
            -webkit-box-shadow: 0 2px rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }
        a.block.block-link-hover1:active {
            -webkit-box-shadow: 0 2px rgba(0, 0, 0, 0.01);
            box-shadow: 0 2px rgba(0, 0, 0, 0.01);
        }
        a.block.block-link-hover2:hover {
            -webkit-transform: translateY(-2px);
            -ms-transform: translateY(-2px);
            transform: translateY(-2px);
            -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }
        a.block.block-link-hover2:active {
            -webkit-transform: translateY(-1px);
            -ms-transform: translateY(-1px);
            transform: translateY(-1px);
            -webkit-box-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.05);
        }
        a.block.block-link-hover3:hover {
            -webkit-box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }
        a.block.block-link-hover3:active {
            -webkit-box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        }
        .block > .nav-tabs {
            background-color: #f9f9f9;
            border-bottom: none;
        }
        .block > .nav-tabs.nav-tabs-right > li {
            float: right;
        }
        .block > .nav-tabs.nav-justified > li > a {
            margin-bottom: 0;
        }
        .block > .nav-tabs > li {
            margin-bottom: 0;
        }
        .block > .nav-tabs > li > a {
            margin-right: 0;
            padding-top: 12px;
            padding-bottom: 12px;
            color: #646464;
            font-weight: 600;
            border: 1px solid transparent;
            border-radius: 0;
        }
        .block > .nav-tabs > li > a:hover {
            color: #5c90d2;
            background-color: transparent;
            border-color: transparent;
        }
        .block > .nav-tabs > li.active > a,
        .block > .nav-tabs > li.active > a:hover,
        .block > .nav-tabs > li.active > a:focus {
            color: #646464;
            background-color: #fff;
            border-color: transparent;
        }
        .block > .nav-tabs.nav-tabs-alt {
            background-color: transparent;
            border-bottom: 1px solid #e9e9e9;
        }
        .block > .nav-tabs.nav-tabs-alt > li > a {
            -webkit-transition: all 0.15s ease-out;
            transition: all 0.15s ease-out;
        }
        .block > .nav-tabs.nav-tabs-alt > li > a:hover {
            -webkit-box-shadow: 0 2px #5c90d2;
            box-shadow: 0 2px #5c90d2;
        }
        .block > .nav-tabs.nav-tabs-alt > li.active > a,
        .block > .nav-tabs.nav-tabs-alt > li.active > a:hover,
        .block > .nav-tabs.nav-tabs-alt > li.active > a:focus {
            -webkit-box-shadow: 0 2px #5c90d2;
            box-shadow: 0 2px #5c90d2;
        }
        .block .block-content.tab-content {
            overflow: hidden;
        }
        .block-options {
            float: right;
            margin: -3px 0 -3px 15px;
            padding: 0;
            height: 24px;
            list-style: none;
        }
        .block-options:before,
        .block-options:after {
            content: " ";
            display: table;
        }
        .block-options:after {
            clear: both;
        }
        .block-options.block-options-left {
            float: left;
            margin-right: 15px;
            margin-left: 0;
        }
        .block-options.block-options-left + .block-title {
            float: right;
        }
        .block-options > li {
            display: inline-block;
            margin: 0 2px;
            padding: 0;
        }
        .block-options > li > a,
        .block-options > li > button {
            display: block;
            padding: 2px 3px;
            color: #999999;
            opacity: .6;
        }
        .block.block-themed > .block-header .block-options > li > a,
        .block.block-themed > .block-header .block-options > li > button {
            color: #fff;
        }
        .block-options > li > a:hover,
        .block-options > li > button:hover {
            text-decoration: none;
            opacity: 1;
        }
        .block-options > li > a:active,
        .block-options > li > button:active {
            opacity: .6;
        }
        .block-options > li > span {
            display: block;
            padding: 2px 3px;
        }
        .block.block-themed > .block-header .block-options > li > span {
            color: #fff;
        }
        .block-options > li > a:focus {
            text-decoration: none;
            opacity: 1;
        }
        .block-options > li > button {
            background: none;
            border: none;
        }
        .block-options > li.active > a,
        .block-options > li.open > button {
            text-decoration: none;
            opacity: 1;
        }
        .nav-main {
            margin: 0 -20px;
            padding: 0;
            list-style: none;
        }
        .nav-main .nav-main-heading {
            padding: 22px 35px 6px 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.3);
        }
        .nav-main a {
            display: block;
            padding: 10px 35px 10px 20px;
            color: rgba(255, 255, 255, 0.5);
        }
        .nav-main a:hover,
        .nav-main a:focus {
            color: rgba(255, 255, 255, 0.5);
            background-color: rgba(0, 0, 0, 0.2);
        }
        .nav-main a:hover > i,
        .nav-main a:focus > i {
            color: #fff;
        }
        .nav-main a.active,
        .nav-main a.active:hover {
            color: #fff;
        }
        .nav-main a.active > i,
        .nav-main a.active:hover > i {
            color: #fff;
        }
        .nav-main a > i {
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.2);
        }
        .nav-main a.nav-submenu {
            position: relative;
            padding-right: 30px;
        }
        .nav-main a.nav-submenu:before {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            right: 15px;
            display: inline-block;
            font-family: 'FontAwesome';
            color: rgba(255, 255, 255, 0.25);
            content: "\f104";
        }
        .nav-main a.nav-submenu:before.nav-main a.nav-submenu:before-fwidth {
            width: 100%;
        }
        .nav-main ul {
            margin: 0;
            padding: 0 0 0 50px;
            height: 0;
            list-style: none;
            background-color: rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .nav-main ul > li {
            opacity: 0;
            -webkit-transition: all 0.25s ease-out;
            transition: all 0.25s ease-out;
            -webkit-transform: translateX(-15px);
            -ms-transform: translateX(-15px);
            transform: translateX(-15px);
        }
        .nav-main ul .nav-main-heading {
            padding-left: 0;
            padding-right: 0;
            color: rgba(255, 255, 255, 0.65);
        }
        .nav-main ul a {
            padding: 8px 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
        }
        .nav-main ul a:hover,
        .nav-main ul a:focus {
            color: #fff;
            background-color: transparent;
        }
        .nav-main ul a > i {
            margin-right: 10px;
        }
        .nav-main ul ul {
            padding-left: 12px;
        }
        .nav-main li.open > a.nav-submenu {
            color: #fff;
        }
        .nav-main li.open > a.nav-submenu > i {
            color: #fff;
        }
        .nav-main li.open > a.nav-submenu:before {
            content: "\f107";
        }
        .nav-main li.open > ul {
            height: auto;
        }
        .nav-main li.open > ul > li {
            opacity: 1;
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        .nav-header {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .nav-header:before,
        .nav-header:after {
            content: " ";
            display: table;
        }
        .nav-header:after {
            clear: both;
        }
        .nav-header > li {
            margin-right: 12px;
            float: left;
        }
        .nav-header > li > a,
        .nav-header > li > .btn-group > a {
            padding: 0 12px;
            display: block;
            line-height: 34px;
            font-weight: 600;
        }
        .nav-header.pull-right > li {
            margin-right: 0;
            margin-left: 12px;
            float: left;
        }
        .nav-header .header-content {
            line-height: 34px;
        }
        .nav-header .header-search {
            width: 360px;
        }
        @media screen and (max-width: 767px) {
            .nav-header .header-search {
                display: none;
            }
            .nav-header .header-search.header-search-xs-visible {
                position: absolute;
                top: 60px;
                right: 0;
                left: 0;
                z-index: 999;
                display: block;
                width: 100%;
                border-top: 1px solid #f9f9f9;
            }
            .nav-header .header-search.header-search-xs-visible > form {
                padding: 14px 14px;
                background-color: #fff;
                -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
            }
        }
        .nav-users {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .nav-users > li:last-child > a {
            border-bottom: none;
        }
        .nav-users a {
            position: relative;
            padding: 12px 8px 8px 71px;
            display: block;
            min-height: 62px;
            font-weight: 600;
            border-bottom: 1px solid #f3f3f3;
        }
        .nav-users a > img {
            position: absolute;
            left: 12px;
            top: 10px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }
        .nav-users a > i {
            position: absolute;
            left: 40px;
            top: 40px;
            display: inline-block;
            width: 18px;
            height: 18px;
            line-height: 18px;
            text-align: center;
            background-color: #fff;
            border-radius: 50%;
        }
        .nav-users a:hover {
            background-color: #f9f9f9;
        }
        .list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .list > li {
            position: relative;
        }
        .list-timeline {
            position: relative;
            padding-top: 10px;
        }
        .list-timeline > li {
            margin-bottom: 10px;
        }
        .list-timeline .list-timeline-time {
            margin: 0 -20px;
            padding: 10px 20px 10px 40px;
            min-height: 40px;
            text-align: right;
            color: #999;
            font-size: 13px;
            font-style: italic;
            background-color: #f9f9f9;
            border-radius: 2px;
        }
        .list-timeline .list-timeline-icon {
            position: absolute;
            top: 5px;
            left: 10px;
            width: 30px;
            height: 30px;
            line-height: 30px;
            color: #fff;
            text-align: center;
            border-radius: 50%;
        }
        .list-timeline .list-timeline-content {
            padding: 10px 10px 1px;
        }
        .list-timeline .list-timeline-content > p:first-child {
            margin-bottom: 0;
        }
        @media screen and (min-width: 768px) {
            .list-timeline {
                padding-top: 20px;
            }
            .list-timeline:before {
                position: absolute;
                top: 0;
                left: 120px;
                bottom: 0;
                display: block;
                width: 4px;
                content: "";
                background-color: #f9f9f9;
                z-index: 1;
            }
            .list-timeline > li {
                min-height: 40px;
                z-index: 2;
            }
            .list-timeline > li:last-child {
                margin-bottom: 0;
            }
            .list-timeline .list-timeline-time {
                position: absolute;
                top: 0;
                left: 0;
                margin: 0;
                padding-right: 0;
                padding-left: 0;
                width: 90px;
                background-color: transparent;
            }
            .list-timeline .list-timeline-icon {
                top: 3px;
                left: 105px;
                width: 34px;
                height: 34px;
                line-height: 34px;
                z-index: 2 !important;
            }
            .list-timeline .list-timeline-content {
                padding-left: 160px;
            }
        }
        .list-activity > li {
            margin-bottom: 5px;
            padding-bottom: 5px;
            padding-left: 30px;
            font-size: 13px;
            border-bottom: 1px solid #f3f3f3;
        }
        .list-activity > li > i:first-child {
            position: absolute;
            left: 0;
            top: 0;
            display: inline-block;
            width: 20px;
            height: 20px;
            line-height: 20px;
            font-size: 14px;
            text-align: center;
        }
        .list-events > li {
            margin-bottom: 5px;
            padding: 8px 30px 8px 10px;
            color: rgba(0, 0, 0, 0.5);
            font-size: 13px;
            font-weight: 700;
            background-color: #b5d0eb;
        }
        .list-events > li:before {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            right: 10px;
            display: inline-block;
            font-family: 'FontAwesome';
            color: rgba(255, 255, 255, 0.75);
            content: "\f073";
        }
        .list-events > li:before.list-events > li:before-fwidth {
            width: 100%;
        }
        .list-events > li:hover {
            cursor: move;
        }
        .list-simple > li {
            margin-bottom: 20px;
        }
        .list-simple-mini > li {
            margin-bottom: 10px;
        }
        .list-li-clearfix > li:before,
        .list-li-clearfix > li:after {
            content: " ";
            display: table;
        }
        .list-li-clearfix > li:after {
            clear: both;
        }
        .img-avatar {
            display: inline-block !important;
            width: 64px;
            height: 64px;
            border-radius: 50%;
        }
        .img-avatar.img-avatar32 {
            width: 32px;
            height: 32px;
        }
        .img-avatar.img-avatar48 {
            width: 48px;
            height: 48px;
        }
        .img-avatar.img-avatar96 {
            width: 96px;
            height: 96px;
        }
        .img-avatar.img-avatar128 {
            width: 128px;
            height: 128px;
        }
        .img-avatar-thumb {
            margin: 5px;
            -webkit-box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.4);
        }
        .img-thumb {
            padding: 5px;
            background-color: #fff;
            border-radius: 2px;
        }
        .img-link {
            display: inline-block;
            cursor: -webkit-zoom-in;
            cursor: zoom-in;
            -webkit-transition: -webkit-transform 0.15s ease-out;
            transition: transform 0.15s ease-out;
        }
        .img-link:hover {
            -webkit-transform: rotate(1deg);
            -ms-transform: rotate(1deg);
            transform: rotate(1deg);
        }
        .img-container {
            position: relative;
            overflow: hidden;
            z-index: 0;
            display: block;
        }
        .img-container .img-options {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1;
            content: "";
            background-color: rgba(0, 0, 0, 0.6);
            opacity: 0;
            visibility: none;
            -webkit-transition: all 0.25s ease-out;
            transition: all 0.25s ease-out;
        }
        .img-container .img-options-content {
            position: absolute;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            right: 0;
            left: 0;
            text-align: center;
        }
        .img-container > img {
            -webkit-transition: -webkit-transform 0.35s ease-out;
            transition: transform 0.35s ease-out;
        }
        .img-container:hover .img-options {
            opacity: 1;
            visibility: visible;
        }
        .img-container.fx-img-zoom-in:hover > img {
            -webkit-transform: scale(1.2);
            -ms-transform: scale(1.2);
            transform: scale(1.2);
        }
        .img-container.fx-img-rotate-r:hover > img {
            -webkit-transform: scale(1.4) rotate(8deg);
            -ms-transform: scale(1.4) rotate(8deg);
            transform: scale(1.4) rotate(8deg);
        }
        .img-container.fx-img-rotate-l:hover > img {
            -webkit-transform: scale(1.4) rotate(-8deg);
            -ms-transform: scale(1.4) rotate(-8deg);
            transform: scale(1.4) rotate(-8deg);
        }
        .img-container.fx-opt-slide-top .img-options {
            -webkit-transform: translateY(100%);
            -ms-transform: translateY(100%);
            transform: translateY(100%);
        }
        .img-container.fx-opt-slide-top:hover .img-options {
            -webkit-transform: translateY(0);
            -ms-transform: translateY(0);
            transform: translateY(0);
        }
        .img-container.fx-opt-slide-right .img-options {
            -webkit-transform: translateX(-100%);
            -ms-transform: translateX(-100%);
            transform: translateX(-100%);
        }
        .img-container.fx-opt-slide-right:hover .img-options {
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        .img-container.fx-opt-slide-down .img-options {
            -webkit-transform: translateY(-100%);
            -ms-transform: translateY(-100%);
            transform: translateY(-100%);
        }
        .img-container.fx-opt-slide-down:hover .img-options {
            -webkit-transform: translateY(0);
            -ms-transform: translateY(0);
            transform: translateY(0);
        }
        .img-container.fx-opt-slide-left .img-options {
            -webkit-transform: translateX(100%);
            -ms-transform: translateX(100%);
            transform: translateX(100%);
        }
        .img-container.fx-opt-slide-left:hover .img-options {
            -webkit-transform: translateX(0);
            -ms-transform: translateX(0);
            transform: translateX(0);
        }
        .img-container.fx-opt-zoom-in .img-options {
            -webkit-transform: scale(0);
            -ms-transform: scale(0);
            transform: scale(0);
        }
        .img-container.fx-opt-zoom-in:hover .img-options {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }
        .img-container.fx-opt-zoom-out .img-options {
            -webkit-transform: scale(2);
            -ms-transform: scale(2);
            transform: scale(2);
        }
        .img-container.fx-opt-zoom-out:hover .img-options {
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        @media print {
            #page-container,
            #main-container {
                padding: 0 !important;
            }
            #header-navbar,
            #sidebar,
            #side-overlay,
            .block-options {
                display: none !important;
            }
        }



    </style>
</head>
<body>
<!-- Page Content -->
<!-- Invoice -->
<!--<div class="block">-->
<div style="padding: 50px">
    <div class="block-header">
        <ul class="block-options">
            <li>
                <!-- Print Page functionality is initialized in App() -> uiHelperPrint() -->
                <button type="button" onclick="App.initHelper('print-page');"></button>
            </li>
            <li>
                <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"></button>
            </li>
            <li>
                <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
            </li>
        </ul>

        <img  style="width: 200px;text-align: center" src="http://mercagest.com/images/logo.png">
    </div>
    <div class="block-content block-content-narrow">
        <!-- Invoice Info -->
        <div class="h1 text-center push-30-t push-30 hidden-print">Rebut</div>
        <div class="h4 text-center push-30-t push-30 hidden-print">Data pagament:
            <b>{{ $data['dataPagat'] }}</b></div>
        <hr class="hidden-print">

        <div class="h4 text-center push-30-t push-30 hidden-print">{{ $data['tenant_name'] }}
            <br><b>NIF : {{ $data['tenant_nif'] }}</b></div>

        <hr class="hidden-print">

        <!-- END Invoice Info -->

        <table>
            <thead></thead>
            <tbody>
            <tr>
                <td style="width:40%">
                    <h4><b>{{ $data['nomMercat'] }}</b></h4>
                    Tel: {{ $data['tenant_phone'] }}<br><br>
                </td>
                <td style="padding-left:180px;width:60%" class="text-right">
                    <h4><b>Client:</b></h4>
                    {{ $data['name'] }}<br>
                    {{ $data['address'] }}<br>
                    {{ $data['dni'] }}<br>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- Table --><br><br>
        <hr class="hidden-print">

        <div>
            <h4><b>Rebut:</b></h4>
            Dia Mercat: <b>{{ $data['diaMercat'] }}</b><br>
            Parada:     <b>{{ $data['numParada'] }}</b><br>
            Metres:     <b>{{ $data['metresParada'] }} m</b><br>
            Preu:       <b>{{ $data['preuXmetre'] }} €</b><br>
            Periode:        <b>{{ $data['mes'] }}</b><br>
        </div>
        <hr class="hidden-print">
        <div>
            <h4>Total: <b>{{ $data['total'] }} €</b></h4>
        </div>
        <br>
        <div>
            <h3 style="color:red">PAGAT!</h3>
        </div>
        <br>
        @if($data['isCopy'])
        <div>
            <h3>COPIA</h3>
        </div>
        @endif

    <!-- END Table -->

        <!-- Footer -->
        <hr class="hidden-print">
        <p class="text-muted text-center"><small>Rebut imprés desde la plataforma de gestió de mercats <b>Mercagest</b></small></p>
        <p class="text-muted text-center"><small>http://www.mercagest.com</small></p>
        <!-- END Footer -->

    </div>
</div></div>
<!-- END Invoice -->
<!-- END Page Content -->
<!-- END Main Container -->

<!-- END Page Container -->



<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->

</body>
</html>
