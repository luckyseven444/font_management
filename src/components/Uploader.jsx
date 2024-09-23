import { useRef, useContext } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUpload } from '@fortawesome/free-solid-svg-icons';
import opentype from 'opentype.js';
import { updateLocalCSS } from '../libs/uploadLocalCSS';
import FontsContext from '../contexts/fontsContexts';

function Uploader() {
  const fileInputRef = useRef(null);
  const [fonts, setFonts] = useContext(FontsContext);

  const handleDivClick = () => {
    fileInputRef.current.click();
  };

  const handleFileChange = async (event) => {
    const file = event.target.files[0];

    if (file) {
      const fileExtension = file.name.split('.').pop().toLowerCase();
      if (fileExtension !== 'ttf') {
        alert('Please upload a .ttf file.');
        fileInputRef.current.value = '';
        return;
      }

      try {
        const reader = new FileReader();
        reader.onload = async (e) => {
          const arrayBuffer = e.target.result;
          const font = opentype.parse(arrayBuffer);
          
          // Update local App.css
          updateLocalCSS(font.names.fullName.en, file.name);
          
          const formData = new FormData();
          formData.append('file', file);
          formData.append('fileName', file.name);
          formData.append('fontName', font.names.fullName.en);

          const response = await fetch(`http://localhost:8080/server/upload`, {
            method: 'POST',
            body: formData,
          });

          if (response.ok) {
            setFonts([...fonts, { file_name: file.name, font_name: font.names.fullName.en }]);
          } else {
            alert('File upload failed.');
          }
        };

        reader.readAsArrayBuffer(file);

      } catch (error) {
        console.error('Error uploading file:', error);
      }
    }
  };

  return (
    <div
      style={{
        width: '300px',
        height: '100px',
        border: '1px solid black',
        cursor: 'pointer',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
      }}
      onClick={handleDivClick}
    >
      <span>
        <FontAwesomeIcon icon={faUpload} /> Click to Upload
      </span>
      <input
        type="file"
        name="upload"
        ref={fileInputRef}
        style={{ display: 'none' }}
        accept=".ttf"
        onChange={handleFileChange}
      />
    </div>
  );
}

export default Uploader;
