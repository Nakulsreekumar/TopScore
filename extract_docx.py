import zipfile
import xml.etree.ElementTree as ET
import sys

def get_docx_text(path):
    try:
        document = zipfile.ZipFile(path)
        xml_content = document.read('word/document.xml')
        document.close()
        tree = ET.XML(xml_content)
        
        WORD_NAMESPACE = '{http://schemas.openxmlformats.org/wordprocessingml/2006/main}'
        PARA = WORD_NAMESPACE + 'p'
        TEXT = WORD_NAMESPACE + 't'
        
        paragraphs = []
        for paragraph in tree.iter(PARA):
            texts = [node.text
                     for node in paragraph.iter(TEXT)
                     if node.text]
            if texts:
                paragraphs.append(''.join(texts))
        
        return '\n'.join(paragraphs)
    except Exception as e:
        return f"Error reading {path}: {str(e)}"

if __name__ == '__main__':
    if len(sys.argv) > 1:
        text = get_docx_text(sys.argv[1])
        with open('FSD_Report_Text.txt', 'w', encoding='utf-8') as f:
            f.write(text)
        print("Successfully extracted to FSD_Report_Text.txt")
    else:
        print("Provide path to docx")
