import React from 'react';
import '../index.css';
import loginImage from '../images/Login.png';
import logoImage from '../images/logo.png';
import usernameImage from '../images/username.png';

const LoginComponent = () => {
  return (
    <div className="body"> {/* Use className instead of style for class-based styles */}
      <div className="login-div">
        <div className="login-inner-div">
          <img src={logoImage} alt="logo" className="logo" />
          <p className="titleText">Speed Up Garage Inventory<br />and POS System</p>
          <div>
            <label htmlFor="uname" className="label">Username</label>
            <input type="text" placeholder="Enter Username" name="uname" className="input" required />
            <label htmlFor="psw" className="label">Password</label>
            <input type="password" placeholder="Enter Password" name="psw" className="password input" required />
            <button className="login-button">Login</button>
          </div>
          <div>
            <span className="error" id="error">This is for error handling</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginComponent;