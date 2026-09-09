import os, re
root = r'd:\Personal\MASHULLUE\NewVersion\talanta-site-full-update\live-site'
pats = [
    re.compile(r'(?:href|src)=["\']([^"\']+)["\']', re.I),
    re.compile(r'(?:href|src)=([^\s>]+)', re.I),
]
missing = set()
for dirpath, _, files in os.walk(root):
    for fn in files:
        if not fn.lower().endswith(('.html', '.php')):
            continue
        path = os.path.join(dirpath, fn)
        try:
            text = open(path, 'r', encoding='utf-8').read()
        except Exception:
            try:
                text = open(path, 'r', encoding='latin-1').read()
            except Exception:
                continue
        refs = []
        for p in pats:
            refs += p.findall(text)
        for ref in refs:
            ref = str(ref).strip()
            if not ref or ref.startswith(('http:','https:','mailto:','tel:','javascript:','#','data:','//')):
                continue
            if ref.startswith('php?'):
                continue
            url = ref.split('#',1)[0].split('?',1)[0]
            if not url:
                continue
            target = os.path.normpath(os.path.join(os.path.dirname(path), url))
            if not os.path.exists(target):
                missing.add((os.path.relpath(path, root), ref))
print('MISSING_COUNT=' + str(len(missing)))
for f, ref in sorted(missing):
    print(f'{f} -> {ref}')
