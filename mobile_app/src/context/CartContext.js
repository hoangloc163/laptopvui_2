import React, { createContext, useState } from 'react';

export const CartContext = createContext();

export const CartProvider = ({ children }) => {
  const [cart, setCart] = useState({});

  const addToCart = (product, quantity = 1) => {
    setCart(prev => ({
      ...prev,
      [product.id_sp]: {
        ...product,
        quantity: (prev[product.id_sp]?.quantity || 0) + quantity
      }
    }));
  };

  const removeFromCart = (idSp) => {
    setCart(prev => {
      const newCart = { ...prev };
      delete newCart[idSp];
      return newCart;
    });
  };

  const clearCart = () => setCart({});

  return (
    <CartContext.Provider value={{ cart, addToCart, removeFromCart, clearCart }}>
      {children}
    </CartContext.Provider>
  );
};
