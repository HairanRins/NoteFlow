function escapeHtml(input) {
    return input
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

export function renderMarkdown(input) {
    const escaped = escapeHtml(input ?? '');
    const codeBlocks = [];

    let html = escaped.replace(/```([\s\S]*?)```/g, (_, code) => {
        const token = `__CODE_BLOCK_${codeBlocks.length}__`;
        codeBlocks.push(`<pre class="nf-code"><code>${code.trim()}</code></pre>`);
        return token;
    });

    const lines = html.split('\n');
    const rendered = [];
    let listOpen = false;

    for (const line of lines) {
        if (/^\s*-\s+/.test(line)) {
            if (!listOpen) {
                rendered.push('<ul class="nf-list">');
                listOpen = true;
            }

            rendered.push(`<li>${line.replace(/^\s*-\s+/, '')}</li>`);
            continue;
        }

        if (listOpen) {
            rendered.push('</ul>');
            listOpen = false;
        }

        if (/^###\s+/.test(line)) {
            rendered.push(`<h3>${line.replace(/^###\s+/, '')}</h3>`);
            continue;
        }

        if (/^##\s+/.test(line)) {
            rendered.push(`<h2>${line.replace(/^##\s+/, '')}</h2>`);
            continue;
        }

        if (/^#\s+/.test(line)) {
            rendered.push(`<h1>${line.replace(/^#\s+/, '')}</h1>`);
            continue;
        }

        if (line.trim() === '') {
            rendered.push('<p></p>');
            continue;
        }

        rendered.push(`<p>${line}</p>`);
    }

    if (listOpen) {
        rendered.push('</ul>');
    }

    html = rendered.join('');
    html = html
        .replace(/\[\[([^\[\]]+)\]\]/g, '<span class="nf-link">[[$1]]</span>')
        .replace(/(^|\s)#([A-Za-z0-9\-_]+)/g, '$1<span class="nf-tag">#$2</span>');

    codeBlocks.forEach((block, index) => {
        html = html.replace(`__CODE_BLOCK_${index}__`, block);
    });

    return html;
}
