import { useState } from 'react';
import Uploader from './components/Uploader';
import FontList from './components/FontList';
import FontsContext from './contexts/fontsContexts';
import FontGroupContext from './contexts/fontGroupContext';
import 'bootstrap/dist/css/bootstrap.min.css';
import Create from './components/Create';
import FontGroup from './components/FontGroup';

function App() {
  const [fonts, setFonts] = useState([]);
  const [group, setGroup] = useState([])
  return (
    <FontsContext.Provider value={[fonts, setFonts]}>
        <FontGroupContext.Provider value={[group, setGroup]}>
            <div className="container">
                <div className="row">
                    <div className="col mt-3">
                        <div className="d-flex justify-content-center">
                        <Uploader />
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col">
                        <FontList />
                    </div>
                </div>
                <div className="row">
                    <div className="col">
                        <Create></Create>
                    </div>
                </div>
                <div className="row">
                    <div className="col">
                        <FontGroup></FontGroup>
                    </div>
                </div>
            </div>
        </FontGroupContext.Provider>
    </FontsContext.Provider>
  );
}

export default App;
