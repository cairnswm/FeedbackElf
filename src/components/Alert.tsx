import React from 'react';

type AlertProps = {
  variant?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info' | 'light' | 'dark';
  className?: string;
  children: React.ReactNode;
};

const Alert = ({ variant = 'primary', className = '', children }: AlertProps) => {
  const variantClass = `alert-${variant}`;
  const fullClass = ['alert', variantClass, className].filter(Boolean).join(' ');

  return <div className={fullClass}>{children}</div>;
};

export default Alert;
