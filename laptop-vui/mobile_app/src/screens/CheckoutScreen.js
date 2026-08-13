import React, { useState, useContext } from 'react';
import { View, Text, TextInput, StyleSheet, TouchableOpacity, Alert, ScrollView } from 'react-native';
import { CartContext } from '../context/CartContext';
import apiClient from '../api/config';

export default function CheckoutScreen({ navigation }) {
  const { cart, clearCart } = useContext(CartContext);
  const [form, setForm] = useState({ hoten: '', email: '', diachi: '', dienthoai: '' });
  const [loading, setLoading] = useState(false);

  const handleCheckout = async () => {
    if (!form.hoten || !form.email || !form.diachi || !form.dienthoai) {
      Alert.alert('Lỗi', 'Vui lòng điền đầy đủ thông tin');
      return;
    }

    setLoading(true);
    try {
      // Prepare cart format for API
      const cartData = {};
      Object.values(cart).forEach(item => {
        cartData[item.id_sp] = item.quantity;
      });

      const response = await apiClient.post('/checkout', {
        ...form,
        cart: cartData
      });

      if (response.data.status === 'success') {
        Alert.alert('Thành công', 'Đơn hàng của bạn đã được đặt thành công!', [
          { 
            text: 'Tuyệt vời', 
            onPress: () => {
              clearCart();
              navigation.navigate('Home');
            }
          }
        ]);
      } else {
        Alert.alert('Lỗi', response.data.message);
      }
    } catch (error) {
      Alert.alert('Lỗi', 'Không thể kết nối đến máy chủ');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.form}>
        <Text style={styles.title}>Thông tin giao hàng</Text>
        
        <Text style={styles.label}>Họ và tên</Text>
        <TextInput 
          style={styles.input} 
          value={form.hoten}
          onChangeText={text => setForm({...form, hoten: text})}
          placeholder="Nhập họ và tên"
        />

        <Text style={styles.label}>Email</Text>
        <TextInput 
          style={styles.input} 
          value={form.email}
          onChangeText={text => setForm({...form, email: text})}
          placeholder="Nhập địa chỉ email"
          keyboardType="email-address"
          autoCapitalize="none"
        />

        <Text style={styles.label}>Số điện thoại</Text>
        <TextInput 
          style={styles.input} 
          value={form.dienthoai}
          onChangeText={text => setForm({...form, dienthoai: text})}
          placeholder="Nhập số điện thoại"
          keyboardType="phone-pad"
        />

        <Text style={styles.label}>Địa chỉ giao hàng</Text>
        <TextInput 
          style={[styles.input, styles.textArea]} 
          value={form.diachi}
          onChangeText={text => setForm({...form, diachi: text})}
          placeholder="Nhập địa chỉ chi tiết"
          multiline
          numberOfLines={3}
        />

        <TouchableOpacity 
          style={[styles.button, loading && styles.buttonDisabled]} 
          onPress={handleCheckout}
          disabled={loading}
        >
          <Text style={styles.buttonText}>{loading ? 'Đang xử lý...' : 'Xác nhận đặt hàng'}</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  form: { padding: 20, backgroundColor: '#fff', margin: 15, borderRadius: 12, shadowColor: '#000', shadowOpacity: 0.1, elevation: 2 },
  title: { fontSize: 20, fontWeight: 'bold', marginBottom: 20, color: '#0f172a' },
  label: { fontSize: 14, fontWeight: 'bold', color: '#334155', marginBottom: 5 },
  input: { borderWidth: 1, borderColor: '#e2e8f0', borderRadius: 8, padding: 12, marginBottom: 15, backgroundColor: '#f8fafc' },
  textArea: { height: 80, textAlignVertical: 'top' },
  button: { backgroundColor: '#16a34a', padding: 15, borderRadius: 12, alignItems: 'center', marginTop: 10 },
  buttonDisabled: { backgroundColor: '#94a3b8' },
  buttonText: { color: '#fff', fontSize: 16, fontWeight: 'bold' }
});
