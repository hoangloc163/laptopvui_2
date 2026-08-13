import React, { useEffect, useState, useContext } from 'react';
import { View, Text, Image, StyleSheet, ScrollView, TouchableOpacity, ActivityIndicator, Alert } from 'react-native';
import apiClient from '../api/config';
import { CartContext } from '../context/CartContext';

export default function ProductDetailScreen({ route, navigation }) {
  const { id } = route.params;
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const { addToCart } = useContext(CartContext);

  useEffect(() => {
    fetchDetail();
  }, [id]);

  const fetchDetail = async () => {
    try {
      const response = await apiClient.get(`/product?id=${id}`);
      if (response.data.status === 'success') {
        setProduct(response.data.data);
      }
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddToCart = () => {
    if (product) {
      addToCart(product, 1);
      Alert.alert('Thành công', 'Đã thêm vào giỏ hàng', [
        { text: 'Tiếp tục mua sắm', style: 'cancel' },
        { text: 'Xem giỏ hàng', onPress: () => navigation.navigate('Cart') }
      ]);
    }
  };

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#2563eb" />
      </View>
    );
  }

  if (!product) {
    return <View style={styles.center}><Text>Không tìm thấy sản phẩm.</Text></View>;
  }

  return (
    <ScrollView style={styles.container}>
      <View style={styles.imageContainer}>
        <Image source={{ uri: product.hinh_url }} style={styles.image} resizeMode="contain" />
      </View>
      <View style={styles.infoContainer}>
        <Text style={styles.title}>{product.ten_sp}</Text>
        <View style={styles.priceContainer}>
          <Text style={styles.price}>
            {new Intl.NumberFormat('vi-VN').format(product.gia_km > 0 ? product.gia_km : product.gia)}đ
          </Text>
          {product.gia_km > 0 && (
            <Text style={styles.oldPrice}>{new Intl.NumberFormat('vi-VN').format(product.gia)}đ</Text>
          )}
        </View>
        <Text style={styles.description}>{product.mota}</Text>
        
        <TouchableOpacity style={styles.button} onPress={handleAddToCart}>
          <Text style={styles.buttonText}>Thêm vào giỏ hàng</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f8fafc' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  imageContainer: { backgroundColor: '#fff', padding: 20, borderBottomWidth: 1, borderColor: '#e2e8f0' },
  image: { width: '100%', height: 300 },
  infoContainer: { padding: 20 },
  title: { fontSize: 24, fontWeight: 'bold', color: '#0f172a', marginBottom: 10 },
  priceContainer: { flexDirection: 'row', alignItems: 'baseline', gap: 10, marginBottom: 20 },
  price: { fontSize: 26, fontWeight: 'bold', color: '#dc2626' },
  oldPrice: { fontSize: 16, color: '#94a3b8', textDecorationLine: 'line-through' },
  description: { fontSize: 16, color: '#475569', lineHeight: 24, marginBottom: 30 },
  button: { backgroundColor: '#2563eb', padding: 15, borderRadius: 12, alignItems: 'center' },
  buttonText: { color: '#fff', fontSize: 16, fontWeight: 'bold' }
});
