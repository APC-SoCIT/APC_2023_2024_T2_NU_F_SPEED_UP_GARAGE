import React from 'react';
import '../index.css';
import loginImage from '../images/Login.png';
import logoImage from '../images/logo.png';
import usernameImage from '../images/username.png';

const LoginComponent = () => {
  return (
    <div className="login-body"> 
      <div className="login-div">
        <div className="login-inner-div">
          <img src={logoImage} alt="logo" className="logo" />
          <p className="title-text">Speed Up Garage Inventory<br />and POS System</p>
            <div>
              <label htmlFor="uname" className="login-label">Username</label>
              <input type="text" placeholder="Username" name="uname" className="login-input" required />
              <label htmlFor="psw" className="login-label">Password</label>
              <input type="password" placeholder="Password" name="psw" className="login-password-input" required />

              <button className="login-button">Login</button>
            </div>
          <div>
            <span className="login-error" id="error">This is for error handling</span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginComponent;