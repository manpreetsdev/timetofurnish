import os
import glob

views_dir = 'resources/views/frontend'
files = glob.glob(os.path.join(views_dir, '**/*.blade.php'), recursive=True)

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if 'rel="nofollow"' in content:
        # We need to remove rel="nofollow" and clean up any trailing or leading spaces
        # Cases:
        # <a rel="nofollow" href=...
        # <a class="btn" rel="nofollow" href=...
        
        new_content = content.replace(' rel="nofollow"', '').replace('rel="nofollow" ', '')
        
        if new_content != content:
            with open(file, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Fixed {file}")

# Fix SeoLinkFixer.php invalid HTML issue
middleware_path = 'app/Http/Middleware/SeoLinkFixer.php'
if os.path.exists(middleware_path):
    with open(middleware_path, 'r', encoding='utf-8') as f:
        content = f.read()
        
    if "href=\"' . e($loginUrl) . ' ' . $attr2 . '>" in content:
        new_content = content.replace(
            "href=\"' . e($loginUrl) . ' ' . $attr2 . '>",
            "href=\"' . e($loginUrl) . '\" ' . $attr2 . '>"
        )
        with open(middleware_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Fixed {middleware_path}")
