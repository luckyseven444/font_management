export const updateLocalCSS = (fontName, fileName) => {
    const cssRule = `
      @font-face {
        font-family: '${fontName}';
        src: url("http://localhost:8080/uploads/${fileName}") format('truetype');
      }
  
      .${fontName.replace(/\s+/g, '-')} {
        font-family: '${fontName}';
      }
    `;
  
    // Create a new style element
    const styleSheet = document.createElement("style");
    
    styleSheet.innerText = cssRule;
  
    // Append it to the document head
    document.head.appendChild(styleSheet);

    console.log('Font-face rule added dynamically!', fontName);
  };
  