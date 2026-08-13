import React, { useContext } from 'react';
import { View, Text, FlatList, Image, StyleSheet, TouchableOpacity, Alert } from 'react-native';
import { CartContext } from '../context/CartContext';

export default function CartScreen({ navigation }) {
  const { cart, removeFromCart } = useContext(CartContext);
  const cartItems = Object.values(cart);

  const calculateTotal = () => {
    return cartItems.reduce((total, item) => {
      const price = item.gia_km > 0 ? item.gia_km : item.gia;
      return total + (price * item.quantity);
    }, 0);
  };

  const renderItem = ({ item }) => (
    <View style={styles.cartItem}>
      <Image source={{ uri: item.hinh_url }} style={styles.image} resizeMode="contain" />
      <View style={styles.info}>
        <Text style={styles.name} numberOfLines={2}>{item.ten_sp}</Text>
        <Text style={styles.price}>
          {new Intl.NumberFormat('vi-VN').format(item.gia_km > 0 ? item.gia_km : item.gia)}đ
        </Text>
        <Text style={styles.quantity}>Số lượng: {item.quantity}</Text>
      </View>
      <TouchableOpacity style={styles.removeBtn} onPress={() => removeFromCart(item.id_sp)}>
        <Text style={styles.removeText}>Xóa</Text>
      </TouchableOpacity>
    </View>
  );

  if (cartItems.length === 0) {
    return (
      <View style={styles.center}>
        <Text style={styles.emptyText}>Giỏ hàng của bạn đang trống</Text>
        <TouchableOpacity style={styles.shopBtn} onPress={() => navigation.navigate('Home')}>
          <Text style={styles.shopBtnText}>Tiếp tục mua sắm</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={cartItems}
        keyExtractor={(item) => item.id_sp.toString()}
        renderItem={renderItem}
        contentContainerStyle={styles.list}
      />
      <View style={styles.footer}>
        <View style={styles.totalContainer}>
          <Text style={styles.totalText}>Tổng cộng:</Text>
          <Text style={styles.totalPrice}>{new Intl.NumberFormat('vi-VN').format(calculateTotal())}đ</Text>
        </View>
        <TouchableOpacity style={styles.checkoutBtn} onPress={() => navigation.navigate('Checkout')}>
          <Text style={styles.checkoutText}>Tiến hành thanh toán</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  emptyText: { fontSize: 18, color: '#64748b', marginBottom: 20 },
  shopBtn: { backgroundColor: '#2563eb', padding: 12, borderRadius: 8 },
  shopBtnText: { color: '#fff', fontWeight: 'bold' },
  list: { padding: 10 },
  cartItem: { flexDirection: 'row', backgroundColor: '#fff', padding: 10, borderRadius: 12, marginBottom: 10, alignItems: 'center' },
  image: { width: 80, height: 80, marginRight: 10 },
  info: { flex: 1 },
  name: { fontWeight: 'bold', fontSize: 14, color: '#0f172a', marginBottom: 5 },
  price: { color: '#dc2626', fontWeight: 'bold', marginBottom: 5 },
  quantity: { color: '#64748b', fontSize: 12 },
  removeBtn: { padding: 10 },
  removeText: { color: '#dc2626', fontWeight: 'bold' },
  footer: { backgroundColor: '#fff', padding: 20, borderTopWidth: 1, borderColor: '#e2e8f0' },
  totalContainer: { flexDirection: 'row', justifyContent: 'space-between', marginBottom: 15 },
  totalText: { fontSize: 16, fontWeight: 'bold' },
  totalPrice: { fontSize: 20, fontWeight: 'bold', color: '#dc2626' },
  checkoutBtn: { backgroundColor: '#16a34a', padding: 15, borderRadius: 12, alignItems: 'center' },
  checkoutText: { color: '#fff', fontSize: 16, fontWeight: 'bold' }
});
