import React from 'react'

const Footer = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = today.toLocaleString('default', { month: 'long' });

    return (
        <footer>
        <p>Copyright &copy; {year} {month}</p>
        </footer>
    )
}

export default Footer