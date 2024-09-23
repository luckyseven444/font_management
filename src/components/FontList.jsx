import { useContext } from 'react';
import FontsContext from '../contexts/fontsContexts';

function FontList() {
  const [fonts] = useContext(FontsContext);
  console.log('fonts in Fontlist', fonts)
  return (
    <div>
      <h2>Uploaded Fonts</h2>
      <table className="table">
        <thead>
            <tr>
            <th scope="col">Font Name</th>
            <th scope="col">Previes</th>
            <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            {fonts.map((font, index) => (
            <tr key={index}>
                <td>{font.font_name}</td>
                <td> <div style={{ fontFamily: font.font_name }}><span>Example Style</span></div></td>
                <td><button type="button" class="btn btn-link">Link</button></td>
            </tr>

            ))}
            
        </tbody>
      </table>
    </div>
  );
}

export default FontList;
