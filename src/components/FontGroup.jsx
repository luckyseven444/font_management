import { useContext } from "react"
import FontGroupContext from "../contexts/fontGroupContext"
const FontGroup = ()=>{
  const [group, setGroup] = useContext(FontGroupContext)
  
  return (
    <table className="table">
    <thead>
        <tr>
            <th scope="col">Group name</th>
            <th scope="col">Fonts</th>
            <th scope="col">Count</th>
            <th>Actions</th>
        </tr>
    </thead>
   
    <tbody>
        {group.map((group, index) => (
        <tr key={index}>
            <td>{group.group_name}</td>
            <td> {group.font_names}</td>
             <td>{group.count}</td>
             <td>
                <button type="button" className="btn btn-link">Link</button>
                <button type="button" className="btn btn-link">Link</button>
             </td>
        </tr>

        ))}
        
    </tbody>
  </table>
  )
}

export default FontGroup