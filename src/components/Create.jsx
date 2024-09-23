import React, { useState, useContext } from 'react';
import FontsContext from '../contexts/fontsContexts'; // Assuming fonts are provided via context
import FontGroupContext from '../contexts/fontGroupContext';

function Create() {
  const [fonts] = useContext(FontsContext);  
  const [groupName, setGroupName] = useState('');
  const [rows, setRows] = useState([{ text: '', font: '', size: '', priceChange: '' }]);
  const [group, setGroup] = useContext(FontGroupContext)
  // Add a new row
  const addRow = () => {
    setRows([...rows, { text: '', font: '', size: '', priceChange: '' }]);
  };

  // Remove a row by index
  const removeRow = (index) => {
    setRows(rows.filter((_, i) => i !== index));
  };

  // Handle input changes
  const handleInputChange = (index, field, value) => {
    const updatedRows = rows.map((row, i) => 
      i === index ? { ...row, [field]: value } : row
    );
    setRows(updatedRows);
  };

  // Handle form submission
  const handleSubmit = async () => {
    if (rows.length < 2) {
      alert('At least two rows need to be filled out.');
      return;
    }

    // Check if rows are filled
    const filledRows = rows.filter(row => row.text && row.font && row.size);
    if (filledRows.length < 2) {
      alert('Please fill in at least two rows.');
      return;
    }

    // Prepare data for submission
    const fontsList = filledRows.map(row => row.font).join(', ');
    const groupData = {
      name: groupName,
      fonts: fontsList,
      count: filledRows.length,
    };

    try {
      // Submit the data to the server
      const response = await fetch('http://localhost:8080/server/group/create/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(groupData),
      });

      if (response.ok) {
        alert('Group created successfully!');
        // Reset form
        setGroupName('');
        setRows([{ text: '', font: '', size: '', priceChange: '' }]);
        setGroup([...group, { group_name: groupName, font_names: fontsList, count: filledRows.length }]);
      } else {
        alert('Failed to create group.');
      }
    } catch (error) {
      console.error('Error submitting group:', error);
      alert('Error submitting group.');
    }
  };

  return (
    <div>
      <h2>Create Group</h2>
      <input
        type="text"
        placeholder="Group Name"
        value={groupName}
        onChange={(e) => setGroupName(e.target.value)}
        required
      />

      {rows.map((row, index) => (
        <div key={index}>
          <input
            type="text"
            placeholder="Text"
            value={row.text}
            onChange={(e) => handleInputChange(index, 'text', e.target.value)}
          />
          <select
            value={row.font}
            onChange={(e) => handleInputChange(index, 'font', e.target.value)}
          >
            <option value="">Select Font</option>
            {fonts.map((font, i) => (
              <option key={i} value={font.font_name}>{font.font_name}</option>
            ))}
          </select>
          <select
            value={row.size}
            onChange={(e) => handleInputChange(index, 'size', e.target.value)}
          >
            <option value="">Font Size</option>
            {[...Array(11).keys()].map(size => (
              <option key={size} value={size}>{size}</option>
            ))}
          </select>
          <select
            value={row.priceChange}
            onChange={(e) => handleInputChange(index, 'priceChange', e.target.value)}
          >
            <option value="">Price Change</option>
            {[0, 1, 2].map(change => (
              <option key={change} value={change}>{change}</option>
            ))}
          </select>
          {index > 0 && (
            <button type="button" onClick={() => removeRow(index)}>Remove</button>
          )}
        </div>
      ))}

      <button type="button" onClick={addRow}>Add More</button>
      <button type="button" onClick={handleSubmit}>Create</button>
    </div>
  );
}

export default Create;
