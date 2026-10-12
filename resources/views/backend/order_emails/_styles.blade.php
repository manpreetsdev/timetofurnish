    <style>
        .set-screen { color: #39322a; }
        .set-hero, .set-card, .set-stat {
            background: #fff;
            border: 1px solid #e8e5e1;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(104, 91, 78, 0.05);
        }
        .set-hero {
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            padding: 22px 24px; margin-bottom: 14px;
        }
        .set-eyebrow { display: block; color: #8c7e70; font-size: 11px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; }
        .set-hero h1 { margin: 3px 0 5px; font-size: 24px; font-weight: 700; color: #39322a; }
        .set-hero p { margin: 0; max-width: 560px; color: #8c7e70; font-size: 13px; line-height: 1.5; }
        .set-hero-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
        .set-screen .set-btn {
            display: inline-flex; align-items: center; gap: 6px; min-height: 38px; padding: 0 14px;
            border: 1px solid #dcd4c9; border-radius: 8px; background: #f3efe9; color: #685b4e;
            font-size: 13px; font-weight: 600; white-space: nowrap;
        }
        .set-screen .set-btn:hover { background: #ece5db; color: #685b4e; }
        .set-screen .set-btn-primary { background: #685b4e; border-color: #685b4e; color: #fff; }
        .set-screen .set-btn-primary:hover { background: #4f453b; color: #fff; }
        .set-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 14px; }
        .set-stat { padding: 14px 16px; }
        .set-stat span { display: block; color: #8c7e70; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
        .set-stat strong { display: block; margin-top: 4px; font-size: 22px; font-weight: 700; }
        .set-stat.is-warning { border-color: #f0d9a8; background: #fffaf0; }
        .set-stat.is-warning strong { color: #9a6700; }
        .set-card { padding: 18px; margin-bottom: 14px; }
        .set-card-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
        .set-card-head h2 { margin: 0; font-size: 16px; font-weight: 700; color: #39322a; }
        .set-card-head p { margin: 3px 0 0; color: #8c7e70; font-size: 12.5px; line-height: 1.5; }
        .set-label { font-size: 12.5px; font-weight: 700; color: #4a4239; }
        .set-pill {
            display: inline-block; padding: 4px 10px; border-radius: 999px; background: #f3efe9; color: #685b4e;
            font-size: 11.5px; font-weight: 600; line-height: 1.35; white-space: normal;
        }
        .set-pill.is-general { background: #eef2f6; color: #475569; }
        .set-pill.is-on { background: #e8f7ee; color: #137333; }
        .set-pill.is-off { background: #f1f1f1; color: #777; }
        .set-row-editing td { background: #fffaf0 !important; }
        .set-steps { margin: 0; padding-left: 18px; font-size: 13px; line-height: 1.6; color: #4a4239; }
        .set-steps li { margin-bottom: 6px; }
        .set-placeholders { display: grid; gap: 6px; }
        .set-placeholder {
            display: flex; align-items: center; justify-content: space-between; gap: 10px; width: 100%;
            padding: 7px 10px; border: 1px solid #e8e5e1; border-radius: 8px; background: #faf8f5;
            text-align: left; cursor: pointer; transition: all .15s ease;
        }
        .set-placeholder:hover { border-color: #685b4e; background: #f3efe9; }
        .set-placeholder code { color: #8f562f; font-size: 12px; background: transparent; padding: 0; }
        .set-placeholder span { color: #8c7e70; font-size: 11.5px; text-align: right; }
        .set-note { padding: 9px 11px; border-radius: 8px; background: #fffaf0; border: 1px solid #f0d9a8; color: #7a5a12; font-size: 11.5px; line-height: 1.5; }
        .set-preview-frame { width: 100%; height: 70vh; border: 1px solid #e8e5e1; border-radius: 8px; background: #f2f2f0; }
        .set-preview-meta { padding: 10px 12px; margin-bottom: 10px; border-radius: 8px; background: #faf8f5; border: 1px solid #e8e5e1; font-size: 12.5px; }
        .set-preview-meta span { color: #8c7e70; }
        .set-from-list { display: grid; gap: 6px; margin-bottom: 10px; }
        .set-from-item { padding: 7px 10px; border: 1px solid #e8e5e1; border-radius: 8px; background: #faf8f5; font-size: 12.5px; }
        .set-back { display: inline-flex; align-items: center; gap: 4px; color: #8c7e70; font-size: 12.5px; font-weight: 600; margin-bottom: 8px; }
        .set-back:hover { color: #685b4e; }
        @media (max-width: 991.98px) {
            .set-hero { flex-direction: column; align-items: flex-start; }
            .set-hero-actions { justify-content: flex-start; }
            .set-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
    </style>
