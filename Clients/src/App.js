import React, { useEffect } from 'react';
import axios from "axios";

const App = () => {

  useEffect(() => {
    axios.get("https://127.0.0.1:8000/api/annonces/1")
    .then((res) => console.log(res.data))
  },[])
  

  return (

    <div>
      
    </div>
  );
};

export default App;