{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/components/layouts/app.blade.php --}}
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
            padding: 11px;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .91);
            box-shadow: 0 19px 57px rgba(0, 0, 0, .15);
            backdrop-filter: blur(19px);
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
            max-height: 337px;
            overflow: auto;
            padding-right: 3px;
        }

        .app-shell .icon-category h3 {
            margin: 0 0 7px;
            color: var(--muted);
            font-size: .91rem;
        }

        .app-shell .selected-icon {
            min-width: 39px;
            justify-content: center;
            gap: 7px;
            max-width: 227px;
            overflow: hidden;
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
            grid-template-columns: 51px minmax(0, 1fr);
            gap: 15px;
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
            width: 51px;
            height: 51px;
        }

        .app-shell .post-avatar img,
        .app-shell .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .app-shell .post-main {
            min-width: 0;
            display: grid;
            gap: 11px;
        }

        .app-shell .post-author {
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

        .app-shell .side-profile-link span:last-child {
            display: grid;
            gap: 3px;
            min-width: 0;
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
            grid-template-columns: minmax(0, 1fr) 39px;
            gap: 7px;
        }

        .app-shell .comment-form input {
            width: 100%;
            border: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
            color: var(--text);
            padding: 9px 11px;
        }

        .app-shell .comment-panel p {
            margin: 11px 0 0;
            color: var(--text);
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
            min-width: 151px;
            padding: 7px;
            border-top: 1px solid rgba(22, 199, 101, .27);
            border-radius: 7px;
            background: rgba(255, 253, 247, .93);
            box-shadow: 0 19px 57px rgba(0, 0, 0, .13);
            backdrop-filter: blur(19px);
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
            border-color: rgba(22, 199, 101, .27);
        }

        .app-shell .module-cover {
            min-height: 117px;
            margin: -19px -19px 0;
            border-radius: 7px 7px 0 0;
            background:
                linear-gradient(117deg, rgba(22, 199, 101, .17), rgba(179, 139, 49, .11)),
                repeating-linear-gradient(137deg, rgba(23, 34, 28, .07) 0 1px, transparent 1px 19px);
            background-position: center;
            background-size: cover;
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

            .app-shell .module-form-sections,
            .app-shell .module-form-grid {
                grid-template-columns: 1fr;
            }

            .app-shell .profile-head {
                margin-top: -37px;
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
            @if(session('status'))<div class="panel" style="margin-bottom:15px;color:var(--brand)">{{ session('status') }}</div>@endif
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
