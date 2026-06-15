{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/layouts/app.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base :title="$title ?? 'Sirraty'">
    <style>
        .app-shell {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                linear-gradient(117deg, rgba(247, 244, 239, .77), rgba(255, 253, 247, .69)),
                url("https://res.cloudinary.com/duja2smra/image/upload/2BG-_Jun_11_2026_05_44_19_PM_ojailg.webp") center / cover fixed no-repeat;
        }

        .app-shell::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(36, 117, 83, .057) 1px, transparent 1px),
                linear-gradient(0deg, rgba(179, 139, 49, .057) 1px, transparent 1px),
                repeating-linear-gradient(137deg, rgba(23, 34, 28, .027) 0 1px, transparent 1px 19px);
            background-size: 73px 73px;
            mask-image: linear-gradient(to bottom, transparent, #000 19%, #000 81%, transparent);
            opacity: .37;
        }

        .app-shell::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background:
                radial-gradient(circle at 19% 29%, rgba(255, 253, 247, .73), transparent 19rem),
                radial-gradient(circle at 79% 71%, rgba(36, 117, 83, .17), transparent 27rem),
                linear-gradient(180deg, rgba(255, 253, 247, .11), rgba(23, 34, 28, .07));
            opacity: .91;
        }

        .app-shell .panel,
        .app-shell .app-cabinet,
        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .theme-button {
            box-shadow: 0 11px 37px rgba(0, 0, 0, .07);
        }

        .app-shell .panel {
            background: rgba(255, 253, 247, .03);
            border-color: #16c765;
            backdrop-filter: blur(11px);
        }

        .app-shell .app-cabinet,
        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .btn,
        .app-shell .theme-button,
        .app-shell .field input,
        .app-shell .field textarea,
        .app-shell .field select,
        .app-shell .empty {
            border-color: #16c765;
        }

        .app-shell .field input,
        .app-shell .field textarea,
        .app-shell .field select {
            background: rgba(255, 253, 247, .03);
        }

        .app-shell .field input:focus,
        .app-shell .field textarea:focus,
        .app-shell .field select:focus {
            background: rgba(22, 199, 101, .07);
            border-color: #16c765;
            box-shadow: 0 0 0 3px rgba(22, 199, 101, .19);
            outline: 0;
        }

        .app-shell .wrap {
            padding: 27px 73px 73px 0;
        }

        .app-shell .app-cabinet {
            position: fixed;
            top: 19px;
            right: 0;
            bottom: 19px;
            z-index: 29;
            display: flex;
            flex-direction: column;
            gap: 7px;
            width: 57px;
            padding: 11px 7px;
            overflow: hidden;
            border: 1px solid #16c765;
            border-right: 0;
            border-radius: 15px 0 0 15px;
            background: rgba(255, 253, 247, .03);
            backdrop-filter: blur(19px);
            transition: width .19s ease, background .19s ease;
        }

        .app-shell .app-cabinet:hover,
        .app-shell .app-cabinet:focus-within {
            width: 271px;
            background: rgba(255, 253, 247, .07);
        }

        .app-shell .cabinet-stack {
            display: grid;
            gap: 7px;
        }

        .app-shell .cabinet-spacer {
            flex: 1;
        }

        .app-shell .cabinet-form {
            margin: 0;
        }

        .app-shell .cabinet-link,
        .app-shell .cabinet-action,
        .app-shell .theme-button {
            display: grid;
            grid-template-columns: 37px 1fr;
            align-items: center;
            gap: 11px;
            width: 100%;
            min-height: 39px;
            padding: 0;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            cursor: pointer;
            white-space: nowrap;
        }

        .app-shell .cabinet-stack .cabinet-link:first-child {
            border-top-color: transparent;
        }

        .app-shell .cabinet-link i,
        .app-shell .cabinet-link svg,
        .app-shell .cabinet-action i,
        .app-shell .theme-button i {
            display: grid;
            place-items: center;
            width: 37px;
            min-height: 37px;
            color: var(--brand);
        }

        .app-shell .cabinet-link svg {
            width: 23px;
            height: 23px;
            margin: 7px;
            fill: none;
            stroke: var(--brand);
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 1.9;
        }

        .app-shell .cabinet-label {
            opacity: 0;
            transform: translateX(7px);
            transition: opacity .17s ease, transform .17s ease;
        }

        .app-shell .app-cabinet:hover .cabinet-label,
        .app-shell .app-cabinet:focus-within .cabinet-label {
            opacity: 1;
            transform: translateX(0);
        }

        .app-shell .cabinet-link:hover,
        .app-shell .cabinet-link:focus-visible,
        .app-shell .cabinet-action:hover,
        .app-shell .cabinet-action:focus-visible,
        .app-shell .theme-button:hover,
        .app-shell .theme-button:focus-visible {
            background: rgba(22, 199, 101, .07);
            outline: 0;
        }

        .app-shell .composer-panel {
            border-color: transparent;
            box-shadow: none;
        }

        .app-shell .composer-icon {
            display: inline-grid;
            place-items: center;
            width: 43px;
            height: 43px;
            margin-bottom: 15px;
            border-radius: 999px;
            color: var(--brand);
            background: rgba(22, 199, 101, .07);
        }

        .app-shell .quill-icon {
            width: 29px;
            height: 29px;
            fill: none;
            stroke: currentColor;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 1.7;
        }

        .app-shell .composer-actions {
            align-items: stretch;
        }

        .app-shell .media-button {
            position: relative;
            display: inline-grid;
            grid-template-columns: auto auto;
            align-items: center;
            gap: 7px;
            min-height: 39px;
            padding: 9px 15px;
            border: 1px solid #16c765;
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            cursor: pointer;
        }

        .app-shell .media-button input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .app-shell .composer-tools {
            position: relative;
        }

        .app-shell .composer-tools summary {
            list-style: none;
        }

        .app-shell .composer-tools summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .picker-panel {
            position: absolute;
            left: 0;
            top: calc(100% + 7px);
            z-index: 19;
            width: min(573px, calc(100vw - 97px));
            max-height: min(73vh, 573px);
            padding: 11px;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .91);
            box-shadow: 0 19px 57px rgba(0, 0, 0, .15);
            backdrop-filter: blur(19px);
            overflow: auto;
            overscroll-behavior: contain;
        }

        .app-shell .composer-tools.picker-opens-up .picker-panel {
            top: auto;
            bottom: calc(100% + 7px);
            box-shadow: 0 -19px 57px rgba(0, 0, 0, .15);
        }

        .app-shell .emoji-row,
        .app-shell .icon-grid,
        .app-shell .media-preview,
        .app-shell .post-media-grid {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .app-shell .emoji-row {
            max-height: 117px;
            overflow: auto;
            padding-right: 3px;
        }

        .app-shell .emoji-button,
        .app-shell .icon-button {
            display: grid;
            place-items: center;
            width: 37px;
            height: 37px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            cursor: pointer;
        }

        .app-shell .emoji-button:hover,
        .app-shell .emoji-button:focus-visible,
        .app-shell .icon-button:hover,
        .app-shell .icon-button:focus-visible,
        .app-shell .icon-button.is-selected {
            background: rgba(22, 199, 101, .07);
            outline: 0;
        }

        .app-shell .icon-search {
            margin: 11px 0;
        }

        .app-shell .icon-grid {
            padding-right: 3px;
        }

        .app-shell .icon-category-list {
            display: grid;
            gap: 15px;
            max-height: min(337px, 43vh);
            overflow: auto;
            padding-right: 3px;
        }

        .app-shell .icon-category h3 {
            margin: 0 0 7px;
            color: var(--muted);
            font-size: .91rem;
        }

        .app-shell .field-with-icons,
        .app-shell .comment-field-wrap {
            position: relative;
            display: block;
            min-width: 0;
        }

        .app-shell .field-icon-preview {
            position: absolute;
            top: 7px;
            right: 9px;
            z-index: 2;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 5px;
            max-width: min(173px, 37%);
            color: var(--brand);
            pointer-events: none;
        }

        .app-shell .field-icon-preview:empty {
            display: none;
        }

        .app-shell .field-with-icons textarea {
            padding-right: min(197px, 41%);
        }

        .app-shell .hashtag-link {
            color: var(--brand);
            font-weight: 700;
        }

        .app-shell .tag-rank {
            display: grid;
            gap: 1px;
            counter-reset: tag-rank;
        }

        .app-shell .tag-rank a {
            counter-increment: tag-rank;
            display: grid;
            grid-template-columns: 19px minmax(0, 1fr) auto;
            align-items: center;
            gap: 5px;
            padding: 3px 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            font-size: .79rem;
            line-height: 1.17;
        }

        .app-shell .tag-rank a span:first-child {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .app-shell .tag-rank a::before {
            content: counter(tag-rank);
            display: grid;
            place-items: center;
            width: 19px;
            height: 19px;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
            color: var(--brand);
            font-size: .67rem;
            font-weight: 800;
        }

        .app-shell .media-preview {
            margin-top: 11px;
            color: var(--muted);
            font-size: .91rem;
        }

        .app-shell .post-media-grid {
            margin: 15px 0;
        }

        .app-shell .post-media-grid img {
            width: min(100%, 317px);
            aspect-ratio: 1.31;
            object-fit: cover;
            border-radius: 7px;
            border-top: 1px solid rgba(22, 199, 101, .27);
        }

        .app-shell .feed-post-grid {
            display: grid;
            grid-template-columns: 43px minmax(0, 1fr);
            gap: 11px;
            align-items: start;
        }

        .app-shell .post-avatar,
        .app-shell .profile-avatar {
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
            color: var(--brand);
            font-weight: 800;
        }

        .app-shell .post-avatar {
            width: 43px;
            height: 43px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
        }

        .app-shell .post-avatar > img,
        .app-shell .post-avatar > span:not(.post-identity) {
            display: grid;
            place-items: center;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
            color: var(--brand);
        }

        .app-shell .post-avatar img,
        .app-shell .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .app-shell .feed-post-grid > .post-avatar > img {
            width: 43px;
            height: 43px;
        }

        .app-shell .post-copy > p {
            flex: 1 1 auto;
            min-width: 0;
        }

        .app-shell .post-main {
            min-width: 0;
            display: grid;
            gap: 11px;
        }

        .app-shell .post-author {
            color: var(--text);
            font-weight: 800;
        }

        .app-shell .interest-layout {
            align-items: start;
        }

        .app-shell .interest-sidebar {
            align-self: start;
        }

        .app-shell .side-profile-link {
            display: grid;
            grid-template-columns: 51px minmax(0, 1fr);
            gap: 11px;
            align-items: center;
            padding: 7px 0 15px;
            border-bottom: 1px solid rgba(22, 199, 101, .19);
            font-weight: 700;
        }

        .app-shell .side-profile-name {
            display: flex;
            align-items: baseline;
            gap: 7px;
            min-width: 0;
            white-space: nowrap;
        }

        .app-shell .side-profile-name > * {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-shell .post-icon {
            display: inline-grid;
            place-items: center;
            width: 37px;
            height: 37px;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
            color: var(--brand);
        }

        .app-shell .post-icon-group {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .app-shell .post-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 11px;
            min-width: 0;
        }

        .app-shell .post-meta-copy {
            display: flex;
            align-items: baseline;
            gap: 7px;
            min-width: 0;
            white-space: nowrap;
        }

        .app-shell .post-meta-copy > * {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-shell .post-menu-row {
            justify-content: flex-end;
        }

        .app-shell .post-copy-line {
            display: block;
            min-width: 0;
        }

        .app-shell .post-identity,
        .app-shell .comment-identity {
            display: grid;
            gap: 3px;
            min-width: 0;
        }

        .app-shell .post-identity .muted,
        .app-shell .comment-identity .muted {
            overflow-wrap: anywhere;
        }

        .app-shell .post-copy {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-width: 0;
        }

        .app-shell .post-actions {
            display: flex;
            gap: 11px;
            align-items: center;
            flex-wrap: wrap;
            color: var(--muted);
        }

        .app-shell .post-actions form {
            margin: 0;
        }

        .app-shell .post-actions button,
        .app-shell .comment-count,
        .app-shell .comment-cabinet summary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 37px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            padding: 7px 11px;
            list-style: none;
        }

        .app-shell .comment-count {
            cursor: default;
            border-top-color: transparent;
            background: transparent;
            color: var(--brand);
        }

        .app-shell .comment-cabinet summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .post-actions button:hover,
        .app-shell .post-actions button:focus-visible,
        .app-shell .post-actions button.is-active,
        .app-shell .comment-cabinet summary:hover,
        .app-shell .comment-cabinet summary:focus-visible {
            color: var(--brand);
            background: rgba(22, 199, 101, .07);
            outline: 0;
        }

        .app-shell .comment-cabinet {
            position: relative;
        }

        .app-shell .comment-panel {
            position: absolute;
            left: 0;
            top: calc(100% + 7px);
            z-index: 18;
            width: min(419px, calc(100vw - 97px));
            padding: 11px;
            border-top: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .93);
            box-shadow: 0 19px 57px rgba(0, 0, 0, .13);
            backdrop-filter: blur(19px);
        }

        .app-shell .comment-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 31px 31px 39px;
            gap: 7px;
            align-items: center;
        }

        .app-shell .comment-form [data-comment-icon-values] {
            display: none;
        }

        .app-shell .comment-form input {
            width: 100%;
            min-width: 0;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            padding: 9px 11px;
        }

        .app-shell .comment-form button {
            display: grid;
            place-items: center;
            width: 39px;
            height: 39px;
            border: 0;
            background: transparent;
            color: var(--brand);
            cursor: pointer;
        }

        .app-shell .comment-form button:hover,
        .app-shell .comment-form button:focus-visible {
            color: var(--gold);
            outline: 0;
        }

        .app-shell .comment-panel p {
            margin: 11px 0 0;
            color: var(--text);
        }

        .app-shell .inline-comment-form {
            margin-top: 11px;
        }

        .app-shell .comment-thread {
            display: grid;
            gap: 7px;
            margin-top: 13px;
            padding-left: 11px;
            border-left: 1px solid rgba(22, 199, 101, .27);
        }

        .app-shell .comment-item {
            display: grid;
            grid-template-columns: 37px minmax(0, 1fr);
            gap: 11px;
            align-items: start;
            padding: 9px 11px;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
        }

        .app-shell .comment-avatar {
            display: grid;
            place-items: center;
            width: 37px;
            height: 37px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(22, 199, 101, .07);
            color: var(--brand);
            font-size: .79rem;
            font-weight: 800;
        }

        .app-shell .comment-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .app-shell .comment-main {
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .app-shell .comment-meta-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 7px;
            min-width: 0;
        }

        .app-shell .comment-meta-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 3px;
            margin-left: auto;
            white-space: nowrap;
        }

        .app-shell .comment-identity {
            display: flex;
            align-items: baseline;
            gap: 7px;
            min-width: 0;
            font-size: .73rem;
            line-height: 1.17;
            white-space: nowrap;
        }

        .app-shell .comment-identity > * {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-shell .comment-author {
            color: var(--brand);
            font-weight: 800;
        }

        .app-shell .comment-item p {
            margin: 0;
            white-space: pre-wrap;
        }

        .app-shell .comment-follow-form {
            margin: 0;
        }

        .app-shell .comment-follow-form button {
            min-height: 27px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: transparent;
            color: var(--brand);
            cursor: pointer;
            font-size: .73rem;
            font-weight: 800;
            padding: 5px 9px;
        }

        .app-shell .comment-follow-form button.is-active {
            color: var(--gold);
        }

        .app-shell .comment-owner-tools,
        .app-shell .comment-owner-action {
            margin: 0;
        }

        .app-shell .comment-owner-tools summary {
            list-style: none;
        }

        .app-shell .comment-owner-tools summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .comment-owner-tools summary,
        .app-shell .comment-owner-action button {
            display: inline-grid;
            place-items: center;
            width: 23px;
            min-width: 23px;
            min-height: 23px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 5px;
            background: transparent;
            color: var(--brand);
            cursor: pointer;
            font-size: .73rem;
            font-weight: 800;
            padding: 0;
        }

        .app-shell .comment-owner-tools form {
            position: absolute;
            z-index: 13;
            display: grid;
            grid-template-columns: minmax(151px, 1fr) 31px;
            gap: 5px;
            width: min(319px, 73vw);
            margin-top: 5px;
            padding: 7px;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .93);
            box-shadow: 0 11px 37px rgba(0, 0, 0, .13);
            backdrop-filter: blur(11px);
        }

        .app-shell .comment-owner-tools input {
            min-height: 31px;
            padding: 5px 7px;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .07);
            color: var(--text);
        }

        .app-shell .comment-owner-tools form button {
            border: 0;
            border-radius: 7px;
            background: var(--brand);
            color: white;
            cursor: pointer;
        }

        .app-shell .comment-icon-strip,
        .app-shell .comment-media-grid,
        .app-shell .comment-media-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            align-items: center;
        }

        .app-shell .comment-icon-strip {
            color: var(--brand);
            font-size: .91rem;
        }

        .app-shell .comment-media-grid img {
            width: 73px;
            height: 73px;
            object-fit: cover;
            border-radius: 7px;
            border-top: 1px solid rgba(22, 199, 101, .27);
        }

        .app-shell .comment-tool-button,
        .app-shell .comment-tool-picker summary {
            display: inline-grid;
            place-items: center;
            width: 31px;
            min-width: 31px;
            height: 31px;
            min-height: 31px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: transparent;
            color: var(--brand);
            cursor: pointer;
            flex: 0 0 31px;
        }

        .app-shell .comment-field-icons {
            top: 50%;
            right: 9px;
            max-width: 91px;
            transform: translateY(-50%);
        }

        .app-shell .comment-field-wrap input {
            padding-right: 97px;
        }

        .app-shell .comment-tool-button {
            position: relative;
            overflow: hidden;
        }

        .app-shell .comment-tool-button input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
        }

        .app-shell .comment-tool-picker {
            position: relative;
        }

        .app-shell .comment-tool-picker summary {
            list-style: none;
        }

        .app-shell .comment-tool-picker summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .comment-picker-panel {
            position: absolute;
            right: 0;
            bottom: calc(100% + 7px);
            z-index: 23;
            width: min(373px, calc(100vw - 53px));
            max-height: min(373px, 57vh);
            padding: 11px;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .93);
            box-shadow: 0 13px 43px rgba(0, 0, 0, .17);
            backdrop-filter: blur(17px);
            overflow: auto;
        }

        .app-shell .comment-media-preview {
            grid-column: 1 / -1;
            color: var(--muted);
            font-size: .71rem;
        }

        .app-shell .comment-panel-static {
            display: grid;
            gap: 11px;
            margin-top: 15px;
            padding-top: 11px;
            border-top: 1px solid rgba(22, 199, 101, .19);
        }

        .app-shell .comment-panel-static form {
            margin: 0;
        }

        .app-shell .post-menu {
            position: relative;
        }

        .app-shell .post-menu summary {
            display: grid;
            place-items: center;
            width: 37px;
            height: 37px;
            border-radius: 999px;
            color: var(--muted);
            cursor: pointer;
            list-style: none;
        }

        .app-shell .post-menu summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .post-menu summary:hover,
        .app-shell .post-menu summary:focus-visible {
            color: var(--brand);
            background: rgba(22, 199, 101, .07);
            outline: 0;
        }

        .app-shell .post-menu-panel {
            position: absolute;
            top: calc(100% + 7px);
            right: 0;
            z-index: 17;
            min-width: 191px;
            padding: 7px;
            border-top: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .93);
            box-shadow: 0 19px 57px rgba(0, 0, 0, .13);
            backdrop-filter: blur(19px);
        }

        .app-shell .post-edit-cabinet {
            border-top: 1px solid rgba(22, 199, 101, .19);
        }

        .app-shell .post-edit-cabinet > summary {
            display: grid;
            grid-template-columns: 19px 1fr;
            gap: 0;
            place-items: initial;
            align-items: center;
            width: 100%;
            height: 37px;
            padding: 0;
            border-radius: 0;
            color: var(--text);
            text-align: left;
        }

        .app-shell .post-edit-cabinet > summary i {
            width: 19px;
            text-align: left;
        }

        .app-shell .post-edit-cabinet[open] > summary {
            color: var(--brand);
        }

        .app-shell .post-edit-cabinet form {
            display: grid;
            gap: 11px;
            width: min(517px, calc(100vw - 73px));
            padding: 11px 0 3px;
        }

        .app-shell .post-edit-cabinet .field {
            margin: 0;
        }

        .app-shell .post-menu-panel form {
            margin: 0;
        }

        .app-shell .post-menu-panel button {
            display: grid;
            grid-template-columns: 27px 1fr;
            align-items: center;
            width: 100%;
            min-height: 37px;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
            background: transparent;
            color: var(--text);
            cursor: pointer;
            text-align: left;
        }

        .app-shell .post-menu-panel form:first-child button {
            border-top-color: transparent;
        }

        .app-shell .post-menu-panel button:hover,
        .app-shell .post-menu-panel button:focus-visible {
            color: var(--brand);
            background: rgba(22, 199, 101, .07);
            outline: 0;
        }

        .app-shell .report-action {
            position: relative;
            display: inline-block;
            font-size: .79rem;
        }

        .app-shell .report-action > summary {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            min-height: 27px;
            color: var(--muted);
            cursor: pointer;
            list-style: none;
        }

        .app-shell .report-action > summary::-webkit-details-marker {
            display: none;
        }

        .app-shell .report-action[open] > summary,
        .app-shell .report-action > summary:hover {
            color: var(--brand);
        }

        .app-shell .report-action form {
            position: absolute;
            right: 0;
            top: calc(100% + 7px);
            z-index: 25;
            width: min(319px, calc(100vw - 31px));
            padding: 11px;
            border: 1px solid rgba(22, 199, 101, .37);
            border-radius: 7px;
            background: color-mix(in srgb, var(--panel) 93%, transparent);
            box-shadow: 0 19px 47px rgba(0, 0, 0, .19);
            backdrop-filter: blur(17px);
        }

        .app-shell .report-action .field {
            margin-bottom: 9px;
        }

        .app-shell .report-action button {
            min-height: 31px;
        }

        .app-shell .comment-meta-row .report-action {
            font-size: .67rem;
        }

        .app-shell .comment-meta-row .report-action > summary {
            gap: 3px;
            min-height: 23px;
            padding: 3px 5px;
        }

        .app-shell .comment-meta-row .report-action > summary i {
            font-size: .71rem;
        }

        .app-shell .comment-meta-row .report-action form {
            width: min(271px, calc(100vw - 31px));
            padding: 9px;
        }

        .app-shell .feed-post {
            padding: 19px 0 0;
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .73);
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        .app-shell .profile-cover {
            min-height: 217px;
            border-radius: 7px;
            background:
                linear-gradient(117deg, rgba(22, 199, 101, .17), rgba(179, 139, 49, .11)),
                repeating-linear-gradient(137deg, rgba(23, 34, 28, .07) 0 1px, transparent 1px 19px);
            background-position: center;
            background-size: cover;
        }

        .app-shell .profile-head {
            display: grid;
            grid-template-columns: 131px minmax(0, 1fr);
            gap: 19px;
            margin-top: -57px;
            align-items: end;
        }

        .app-shell .profile-avatar {
            width: 131px;
            height: 131px;
            border: 3px solid rgba(22, 199, 101, .27);
            font-size: 3rem;
        }

        .app-shell .profile-title {
            display: grid;
            gap: 7px;
            padding-bottom: 11px;
        }

        .app-shell .metric-row,
        .app-shell .chip-row {
            display: flex;
            gap: 11px;
            flex-wrap: wrap;
        }

        .app-shell .metric,
        .app-shell .chip {
            padding: 7px 11px;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
        }

        .app-shell .profile-post {
            border-color: rgba(22, 199, 101, .27);
        }

        .app-shell .module-topbar {
            justify-content: space-between;
            gap: 19px;
            margin-bottom: 19px;
        }

        .app-shell .module-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .app-shell .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
        }

        .app-shell .module-filter-form {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 5px;
            margin: 0;
            max-width: 100%;
        }

        .app-shell .module-filter-form select,
        .app-shell .module-search-field,
        .app-shell .search-select input,
        .app-shell .search-select select {
            min-height: 31px;
            border: 1px solid var(--line);
            border-radius: 7px;
            background: var(--panel);
            color: var(--text);
            font-size: .79rem;
        }

        .app-shell .module-filter-form select {
            flex: 0 1 157px;
            max-width: 157px;
            padding: 5px 9px;
        }

        .app-shell .search-select {
            display: grid;
            gap: 5px;
            position: relative;
        }

        .app-shell .module-filter-form .search-select {
            min-width: 137px;
            max-width: 157px;
        }

        .app-shell .search-select input {
            width: 100%;
            padding: 5px 9px;
        }

        .app-shell .search-select input {
            background: rgba(255, 253, 247, .07);
        }

        .app-shell .search-select select[data-search-select-menu] {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .app-shell .search-select-results {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 5px);
            z-index: 47;
            display: none;
            max-height: 219px;
            overflow: auto;
            border: 1px solid rgba(22, 199, 101, .37);
            border-radius: 7px;
            background: rgba(255, 253, 247, .97);
            box-shadow: 0 15px 37px rgba(0, 0, 0, .13);
            backdrop-filter: blur(19px);
        }

        .app-shell .search-select.is-open .search-select-results {
            display: grid;
        }

        .app-shell .search-select-option {
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .13);
            background: transparent;
            color: var(--text);
            cursor: pointer;
            font-size: .79rem;
            line-height: 1.17;
            padding: 7px 9px;
            text-align: left;
        }

        .app-shell .search-select-option:hover,
        .app-shell .search-select-option:focus-visible {
            background: rgba(22, 199, 101, .09);
            color: var(--brand);
            outline: 0;
        }

        [data-theme="dark"] .app-shell .search-select-results {
            background: rgba(6, 14, 17, .97);
            border-color: rgba(34, 211, 238, .37);
        }

        .app-shell .module-search-field {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            width: min(237px, 31vw);
            padding: 0 9px;
        }

        .app-shell .module-search-field i {
            color: var(--brand);
            font-size: .73rem;
        }

        .app-shell .module-search-field input {
            width: 100%;
            min-width: 0;
            border: 0;
            background: transparent;
            color: var(--text);
            font: inherit;
            outline: 0;
            padding: 0;
        }

        .app-shell .module-filter-toggle,
        .app-shell .module-filter-submit,
        .app-shell .module-filter-clear {
            min-height: 31px;
            font-size: .79rem;
            padding: 5px 9px;
        }

        .app-shell .module-form {
            display: grid;
            gap: 15px;
        }

        .app-shell .module-form-sections {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 19px;
            align-items: start;
        }

        .app-shell .module-form-section {
            display: grid;
            gap: 15px;
            min-width: 0;
            padding-top: 11px;
            border-top: 1px solid rgba(22, 199, 101, .19);
        }

        .app-shell .module-form-section h2 {
            margin: 0;
            color: var(--muted);
            font-size: .97rem;
            font-weight: 800;
        }

        .app-shell .module-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .app-shell .module-profile-grid,
        .app-shell .module-feed {
            display: grid;
            gap: 19px;
        }

        .app-shell .module-profile-grid {
            grid-template-columns: repeat(auto-fit, minmax(273px, 1fr));
            align-items: start;
        }

        .app-shell .module-profile-item,
        .app-shell .module-market-item {
            min-width: 0;
            overflow: hidden;
            border-color: rgba(22, 199, 101, .27);
        }

        .app-shell .module-cover {
            display: block;
            min-height: 117px;
            margin: -19px -19px 0;
            border-radius: 7px 7px 0 0;
            background:
                linear-gradient(117deg, rgba(22, 199, 101, .17), rgba(179, 139, 49, .11)),
                repeating-linear-gradient(137deg, rgba(23, 34, 28, .07) 0 1px, transparent 1px 19px);
            background-position: center;
            background-size: cover;
        }

        .app-shell .module-card-link:hover,
        .app-shell .module-card-link:focus-visible,
        .app-shell .module-item-title a:hover,
        .app-shell .module-item-title a:focus-visible {
            color: var(--brand);
            outline: 0;
        }

        .app-shell .module-profile-head {
            display: grid;
            grid-template-columns: 73px minmax(0, 1fr);
            gap: 15px;
            align-items: end;
            margin-top: -37px;
        }

        .app-shell .module-avatar {
            width: 73px;
            height: 73px;
            border: 3px solid rgba(22, 199, 101, .27);
            background: rgba(255, 253, 247, .73);
        }

        .app-shell .module-profile-copy {
            min-width: 0;
            padding-bottom: 7px;
        }

        .app-shell .module-item-title {
            margin: 0;
            color: var(--text);
            font-size: 1.17rem;
            line-height: 1.27;
            overflow-wrap: anywhere;
        }

        .app-shell .module-card-description {
            display: -webkit-box;
            margin: 0;
            max-height: 7.3em;
            overflow: hidden;
            color: var(--text);
            line-height: 1.47;
            overflow-wrap: anywhere;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
        }

        .app-shell .module-price {
            color: var(--brand);
            white-space: nowrap;
        }

        .app-shell nav[role="navigation"] {
            margin-top: 19px;
            color: var(--text);
        }

        .app-shell nav[role="navigation"] > div {
            display: grid;
            gap: 11px;
        }

        .app-shell nav[role="navigation"] span,
        .app-shell nav[role="navigation"] a {
            min-width: 0;
        }

        .app-shell nav[role="navigation"] a,
        .app-shell nav[role="navigation"] span[aria-current] span,
        .app-shell nav[role="navigation"] span[aria-disabled] span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 39px;
            min-height: 39px;
            padding: 7px 11px;
            border-color: rgba(22, 199, 101, .27);
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            line-height: 1;
        }

        .app-shell nav[role="navigation"] svg {
            width: 19px;
            height: 19px;
            max-width: 19px;
            max-height: 19px;
            flex: 0 0 19px;
        }

        .app-shell nav[role="navigation"] span[aria-current="page"] span {
            border-color: #16c765;
            background: rgba(22, 199, 101, .17);
            color: var(--brand);
            font-weight: 800;
            box-shadow: inset 0 0 0 1px rgba(22, 199, 101, .57), 0 0 17px rgba(22, 199, 101, .19);
        }

        .app-shell nav[role="navigation"] .sm\:hidden,
        .app-shell nav[role="navigation"] .sm\:flex {
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .app-shell .avatar-picker {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(73px, 1fr));
            gap: 11px;
            margin-top: 7px;
        }

        .app-shell .avatar-option {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 97px;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            cursor: pointer;
        }

        .app-shell .avatar-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .app-shell .avatar-option img {
            width: 73px;
            height: 73px;
            border-radius: 50%;
            object-fit: cover;
            transition: transform .19s ease, box-shadow .19s ease;
        }

        .app-shell .avatar-option:has(input:checked) {
            background: rgba(22, 199, 101, .07);
            box-shadow: inset 0 0 0 1px rgba(22, 199, 101, .57);
        }

        .app-shell .avatar-option:has(input:checked) img {
            transform: scale(1.07);
            box-shadow: 0 0 0 3px rgba(22, 199, 101, .27);
        }

        .app-shell .side-card {
            padding: 7px 0;
            border: 0;
            background: transparent;
            box-shadow: none;
            backdrop-filter: none;
        }

        .app-shell .side-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 7px;
        }

        .app-shell .side-card-head .section-title {
            margin: 0;
        }

        .app-shell .side-card-action {
            min-height: 23px;
            border-radius: 7px;
            font-size: .73rem;
            line-height: 1;
            padding: 3px 7px;
        }

        .app-shell .side-card > p {
            margin: 5px 0 0;
        }

        [data-theme="dark"] .app-shell {
            background:
                linear-gradient(117deg, rgba(17, 23, 18, .51), rgba(23, 32, 25, .43)),
                url("https://res.cloudinary.com/duja2smra/image/upload/2BG-_Jun_11_2026_05_44_19_PM_ojailg.webp") center / cover fixed no-repeat;
        }

        [data-theme="dark"] .app-shell::after {
            background:
                radial-gradient(circle at 19% 29%, rgba(255, 253, 247, .13), transparent 17rem),
                radial-gradient(circle at 79% 71%, rgba(22, 199, 101, .11), transparent 27rem),
                linear-gradient(180deg, rgba(255, 253, 247, .03), rgba(23, 34, 28, .03));
            opacity: .57;
        }

        [data-theme="dark"] .app-shell .panel,
        [data-theme="dark"] .app-shell .app-cabinet,
        [data-theme="dark"] .app-shell .cabinet-link,
        [data-theme="dark"] .app-shell .cabinet-action,
        [data-theme="dark"] .app-shell .theme-button,
        [data-theme="dark"] .app-shell .btn,
        [data-theme="dark"] .app-shell .empty,
        [data-theme="dark"] .app-shell .field input,
        [data-theme="dark"] .app-shell .field textarea,
        [data-theme="dark"] .app-shell .field select {
            background: rgba(17, 23, 18, .03);
            border-color: #16c765;
        }

        [data-theme="dark"] .app-shell .cabinet-link,
        [data-theme="dark"] .app-shell .cabinet-action,
        [data-theme="dark"] .app-shell .theme-button,
        [data-theme="dark"] .app-shell .emoji-button,
        [data-theme="dark"] .app-shell .icon-button {
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .cabinet-stack .cabinet-link:first-child {
            border-top-color: transparent;
        }

        [data-theme="dark"] .app-shell .picker-panel {
            border-color: rgba(22, 199, 101, .27);
            background: rgba(17, 23, 18, .91);
        }

        [data-theme="dark"] .app-shell .post-menu summary:hover,
        [data-theme="dark"] .app-shell .post-menu summary:focus-visible,
        [data-theme="dark"] .app-shell .post-menu-panel button:hover,
        [data-theme="dark"] .app-shell .post-menu-panel button:focus-visible {
            background: rgba(22, 199, 101, .07);
        }

        [data-theme="dark"] .app-shell .post-menu-panel {
            border-top-color: rgba(22, 199, 101, .27);
            background: rgba(17, 23, 18, .93);
        }

        [data-theme="dark"] .app-shell .post-menu-panel button {
            border-top-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .field input:focus,
        [data-theme="dark"] .app-shell .field textarea:focus,
        [data-theme="dark"] .app-shell .field select:focus,
        [data-theme="dark"] .app-shell .cabinet-link:hover,
        [data-theme="dark"] .app-shell .cabinet-link:focus-visible,
        [data-theme="dark"] .app-shell .cabinet-action:hover,
        [data-theme="dark"] .app-shell .cabinet-action:focus-visible,
        [data-theme="dark"] .app-shell .theme-button:hover,
        [data-theme="dark"] .app-shell .theme-button:focus-visible {
            border-color: #16c765;
            background: rgba(22, 199, 101, .07);
            box-shadow: 0 0 0 3px rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .media-button,
        [data-theme="dark"] .app-shell .post-media-grid img,
        [data-theme="dark"] .app-shell .avatar-option,
        [data-theme="dark"] .app-shell .module-profile-item,
        [data-theme="dark"] .app-shell .module-market-item,
        [data-theme="dark"] .app-shell .module-avatar,
        [data-theme="dark"] .app-shell .profile-avatar,
        [data-theme="dark"] .app-shell .profile-post {
            border-color: rgba(22, 199, 101, .27);
        }

        [data-theme="dark"] .app-shell .avatar-option:has(input:checked) {
            background: rgba(22, 199, 101, .07);
            box-shadow: inset 0 0 0 1px rgba(22, 199, 101, .57);
        }

        [data-theme="dark"] .app-shell .avatar-option:has(input:checked) img {
            box-shadow: 0 0 0 3px rgba(22, 199, 101, .27);
        }

        [data-theme="dark"] .app-shell .post-actions button,
        [data-theme="dark"] .app-shell .comment-cabinet summary {
            border-top-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .post-actions button:hover,
        [data-theme="dark"] .app-shell .post-actions button:focus-visible,
        [data-theme="dark"] .app-shell .post-actions button.is-active,
        [data-theme="dark"] .app-shell .comment-cabinet summary:hover,
        [data-theme="dark"] .app-shell .comment-cabinet summary:focus-visible {
            background: rgba(22, 199, 101, .07);
        }

        [data-theme="dark"] .app-shell .comment-panel {
            border-top-color: rgba(22, 199, 101, .27);
            background: rgba(17, 23, 18, .93);
        }

        [data-theme="dark"] .app-shell .comment-panel-static {
            border-top-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .comment-form input {
            border-color: rgba(22, 199, 101, .27);
        }

        [data-theme="dark"] .app-shell .metric,
        [data-theme="dark"] .app-shell .chip {
            border-top-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .composer-panel {
            border-color: transparent;
        }

        [data-theme="dark"] .app-shell .feed-post {
            border: 0;
            border-top: 1px solid rgba(22, 199, 101, .73);
            background: transparent;
        }

        [data-theme="dark"] .app-shell .side-card {
            border: 0;
            background: transparent;
        }

        [data-theme="dark"] .app-shell .side-profile-link {
            border-bottom-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell .module-form-section {
            border-top-color: rgba(22, 199, 101, .19);
        }

        [data-theme="dark"] .app-shell nav[role="navigation"] a,
        [data-theme="dark"] .app-shell nav[role="navigation"] span[aria-current] span,
        [data-theme="dark"] .app-shell nav[role="navigation"] span[aria-disabled] span {
            border-color: rgba(22, 199, 101, .27);
            background: rgba(17, 23, 18, .03);
        }

        [data-theme="dark"] .app-shell nav[role="navigation"] span[aria-current="page"] span {
            border-color: #16c765;
            background: rgba(22, 199, 101, .19);
            color: #b9ffd5;
            box-shadow: inset 0 0 0 1px rgba(22, 199, 101, .57), 0 0 17px rgba(22, 199, 101, .23);
        }

        @media (max-width: 830px) {
            .app-shell .wrap {
                padding-right: 57px;
            }

            .app-shell .app-cabinet {
                top: 11px;
                bottom: 11px;
            }

            .app-shell .feed-post-grid,
            .app-shell .profile-head {
                grid-template-columns: 1fr;
            }

            .app-shell .feed-post-grid {
                grid-template-columns: 43px minmax(0, 1fr);
            }

            .app-shell .module-form-sections,
            .app-shell .module-form-grid {
                grid-template-columns: 1fr;
            }

            .app-shell .profile-head {
                margin-top: -37px;
            }

            .app-shell .module-topbar {
                align-items: flex-start;
            }

            .app-shell .module-actions,
            .app-shell .module-filter-form {
                justify-content: flex-start;
                width: 100%;
            }

            .app-shell .module-search-field {
                width: 100%;
            }

            .app-shell .module-filter-form select {
                flex: 1 1 137px;
                max-width: none;
            }

            .app-shell .module-filter-form .search-select {
                flex: 1 1 137px;
                max-width: none;
            }
        }
    </style>
    <div class="shell app-shell">
        <nav class="app-cabinet" aria-label="App navigation">
            <div class="cabinet-stack">
                <a class="cabinet-link" href="{{ route('app.interest') }}">
                    <svg viewBox="0 0 64 64" aria-hidden="true"><path d="M51 7c-13 3-23 11-31 23-5 7-7 15-7 23 8 0 16-2 23-7 12-8 20-18 23-31" /><path d="M51 7c2 7 1 13-3 19-5 9-14 17-27 24" /><path d="M17 47c9-11 17-19 31-31" /><path d="M13 53l13-5" /></svg>
                    <span class="cabinet-label">Interest</span>
                </a>
                <a class="cabinet-link" href="{{ route('app.recap') }}"><i class="fa-solid fa-rotate"></i><span class="cabinet-label">Recap</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'pages') }}"><i class="fa-regular fa-flag"></i><span class="cabinet-label">Pages</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'groups') }}"><i class="fa-solid fa-people-group"></i><span class="cabinet-label">Groups</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'market') }}"><i class="fa-solid fa-store"></i><span class="cabinet-label">Market</span></a>
                <a class="cabinet-link" href="{{ route('app.module', 'messages') }}"><i class="fa-regular fa-message"></i><span class="cabinet-label">Messages</span></a>
                <a class="cabinet-link" href="{{ route('app.privacy') }}"><i class="fa-solid fa-shield-halved"></i><span class="cabinet-label">Privacy</span></a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="cabinet-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-user-shield"></i><span class="cabinet-label">Admin Zone</span></a>
                    @endif
                @endauth
            </div>
            <div class="cabinet-spacer"></div>
            <button class="theme-button" type="button" data-theme-cycle aria-label="Toggle dark mode"><i class="fa-regular fa-moon"></i><span class="cabinet-label">Mode</span></button>
            @auth
                <form class="cabinet-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="cabinet-action" type="submit"><i class="fa-solid fa-arrow-right-from-bracket"></i><span class="cabinet-label">Sign out</span></button>
                </form>
            @endauth
        </nav>
        <main class="wrap">
            @php
                $flashType = collect(['success', 'error', 'warning', 'info', 'status'])->first(fn ($type) => session()->has($type));
                $flashText = $flashType ? session($flashType) : null;
                $flashClass = match ($flashType) {
                    'success' => 'success',
                    'error' => 'error',
                    'warning' => 'warning',
                    'info' => 'info',
                    'status' => str($flashText)->contains(['removed', 'dismissed', 'not available', 'not valid']) ? 'warning' : 'success',
                    default => 'info',
                };
            @endphp
            @if($flashText)<div class="flash-message {{ $flashClass }}" role="status">{{ $flashText }}</div>@endif
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
