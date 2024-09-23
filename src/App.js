import { useState } from 'react';
import Uploader from './components/Uploader';
import FontList from './components/FontList';
import FontsContext from './contexts/fontsContexts';
import 'bootstrap/dist/css/bootstrap.min.css';
import Create from './components/Create';

function App() {
  const [fonts, setFonts] = useState([]);

  return (
    <FontsContext.Provider value={[fonts, setFonts]}>
      <div className="container">
        <div class="row">
            <div class="col mt-3">
                <div class="d-flex justify-content-center">
                <Uploader />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <FontList />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <Create></Create>
            </div>
        </div>
    </div>
    </FontsContext.Provider>
  );
}

export default App;
