# Amazon Affiliate Features Guide

Theme đã được tích hợp đầy đủ các tính năng cho Amazon Affiliate marketing. Dưới đây là hướng dẫn sử dụng:

## 📦 1. Amazon Product Box

### Cách sử dụng:
1. Vào **Posts → Edit Post**
2. Scroll xuống phần **"Amazon Product Information"** meta box
3. Điền thông tin sản phẩm:
   - Product Title
   - ASIN (tùy chọn)
   - Amazon Affiliate URL (quan trọng!)
   - Price
   - Rating (1-5)
   - Product Image URL
   - Prime Available (checkbox)
   - Product Description
   - Price Last Updated (date)

### Shortcode:
- `[amazon_product]` - Hiển thị product box của post hiện tại
- `[amazon_product id="123"]` - Hiển thị product box của post ID 123

### Features:
- ✅ Star rating display
- ✅ Prime badge
- ✅ Price with last updated date
- ✅ Click tracking
- ✅ Schema markup (SEO)
- ✅ Responsive design

---

## 📊 2. Product Comparison Table

### Cách sử dụng shortcode:

```
[product_comparison]
Product Name | Price | Rating | Best For | Buy Link
Product A | $19.99 | 4.5 | Beginners | [Buy Now](https://amazon.com/...)
Product B | $24.99 | 4.8 | Advanced | [Buy Now](https://amazon.com/...)
Product C | $29.99 | 4.2 | Professional | [Buy Now](https://amazon.com/...)
[/product_comparison]
```

### Format:
- Header row: Tên các cột, cách nhau bởi `|`
- Data rows: Mỗi dòng là một sản phẩm, cách nhau bởi `|`
- Links: Sử dụng format Markdown `[Text](URL)`

---

## ⭐ 3. Best Products Section

### Cách sử dụng:
1. Vào **Posts → Edit Post**
2. Scroll xuống phần **"Best Products Section"** meta box
3. Check **"Show Best Products Section"**
4. Điền Section Title (mặc định: "Best Products We Recommend")
5. Click **"Add Product"** để thêm sản phẩm
6. Điền thông tin cho mỗi sản phẩm:
   - Product Title
   - Amazon URL
   - Image URL
   - Price
   - Description

### Vị trí hiển thị:
Section này sẽ hiển thị sau post intro và trước Related Posts.

---

## 🔔 4. Affiliate Disclosure Banner

### Cấu hình:
1. Vào **Appearance → Customize → Affiliate Disclosure**
2. Điền **Disclosure Text** (mặc định có sẵn)
3. Chọn **Disclosure Position**: Top, Bottom, hoặc Both
4. Check **Show Affiliate Disclosure** để bật

### Tự động:
Banner sẽ tự động hiển thị khi post có:
- Amazon product URL
- Best products
- Amazon links trong content

---

## 📱 5. Related Products Widget

### Cách sử dụng:
1. Vào **Appearance → Widgets**
2. Kéo widget **"Related Products"** vào sidebar
3. Điền:
   - Title (mặc định: "Related Products")
   - Number of products (mặc định: 3)

### Features:
- Tự động hiển thị products từ cùng category
- Chỉ hiển thị trên single post pages
- Click tracking tích hợp

---

## 🎠 6. Product Carousel

### Shortcode:
```
[product_carousel category="nail-polish" number="6" title="Featured Products"]
```

### Parameters:
- `category` (optional): Category slug để filter products
- `number` (optional): Số lượng products (mặc định: 6)
- `title` (optional): Title của section (mặc định: "Featured Products")

### Features:
- Slick carousel responsive
- Auto slides
- Navigation arrows và dots
- Hover effects

---

## 📈 7. Click Tracking

### Tự động hoạt động:
- Tất cả affiliate links sẽ được track tự động
- Click data được lưu trong post meta
- Tracking URL format: `?ref=product_type_post_id&post=post_id`

### View tracking data:
Data được lưu trong:
- `_affiliate_clicks` - Array of clicks by reference
- `_total_affiliate_clicks` - Total click count

---

## 🔍 8. Rich Snippets / Schema Markup

### Tự động:
Schema markup được tự động thêm vào `<head>` khi post có Amazon product:
- Product Schema với price, rating, image
- Review Schema với rating
- Offer Schema với availability

### Benefits:
- ✅ Better SEO
- ✅ Rich snippets trong Google search
- ✅ Star ratings trong search results

---

## 📧 9. Email Newsletter

### Shortcode:
```
[newsletter title="Get Nail Ideas Delivered" description="Subscribe..." placeholder="Enter email" button="Subscribe"]
```

### Parameters (all optional):
- `title`: Newsletter title
- `description`: Description text
- `placeholder`: Input placeholder
- `button`: Button text

### Storage:
Subscriber emails được lưu trong `minimal_nails_newsletter_subscribers` option.

**Note:** Để tích hợp với MailChimp/ConvertKit, cần modify function `minimal_nails_subscribe_newsletter()` trong `includes/newsletter-social.php`.

---

## 📲 10. Social Share Buttons

### Shortcode:
```
[social_share platforms="facebook,twitter,pinterest,email" title="Share this post"]
```

### Parameters:
- `platforms`: Comma-separated list (facebook, twitter, pinterest, email, whatsapp)
- `title`: Section title

### Auto Display:
Có thể bật auto-display trong **Customize → Trust Statement → Show Social Share Buttons**

### Features:
- Share với product links
- Pinterest-friendly images
- WhatsApp support

---

## 🎥 11. Video Integration

### YouTube Video Shortcode:
```
[youtube_video id="VIDEO_ID" title="Video Title"]
```
hoặc
```
[youtube_video url="https://youtube.com/watch?v=VIDEO_ID"]
```

### Video Gallery Shortcode:
```
[video_gallery title="Video Tutorials"]
VIDEO_ID_1
VIDEO_ID_2
VIDEO_ID_3
[/video_gallery]
```

### Features:
- Responsive embed (16:9 aspect ratio)
- Multiple videos trong gallery
- Auto extract video ID từ URL

---

## 🖨️ 12. Print-Friendly Templates

### Tự động:
Print CSS được load tự động khi print:
- Ẩn header, footer, navigation
- Ẩn affiliate links và ads
- Optimized layout cho print
- Links hiển thị URLs

### Customize:
Edit `assets/css/print.css` để customize print styles.

---

## 🏷️ 13. Product Categories Taxonomy

### Sử dụng:
1. Vào **Posts → Product Categories**
2. Tạo categories như: Nail Polish, Press-On Nails, Nail Tools, etc.
3. Assign categories cho posts

### URL:
- Archive: `/product-category/category-slug/`
- Có thể customize slug trong taxonomy registration

---

## 💡 14. You May Also Like Products

### Tự động:
Section này tự động hiển thị sau Related Posts với:
- Products từ cùng category
- 6 products mặc định
- Grid layout responsive

### Customize:
Edit function `minimal_nails_display_you_may_also_like()` trong `includes/you-may-also-like.php` để thay đổi số lượng.

---

## 💰 15. Price Update System

### Cách sử dụng:
1. Trong **Amazon Product Information** meta box
2. Click **"Set to Today"** trong **Price Last Updated** field
3. Date sẽ hiển thị trên product box

### Display:
Price hiển thị với format: "Updated Jan 15, 2024"

---

## 🎨 Styling

Tất cả styles được định nghĩa trong:
- `assets/css/amazon-products.css` - Main styles
- `assets/css/print.css` - Print styles
- Sử dụng CSS variables từ `style.css`

### Color Scheme:
- Amazon Orange: `#FF9900`
- Prime Blue: `#146eb4`
- Theme colors: `var(--warm-taupe)`, `var(--nude-beige)`, etc.

---

## 🔧 Advanced Customization

### Customize Functions:
Tất cả functions được tổ chức trong `includes/` folder:
- `amazon-products.php` - Product boxes, comparison, best products
- `affiliate-disclosure.php` - Disclosure banner
- `product-taxonomy.php` - Product categories
- `widgets.php` - Related products widget
- `product-carousel.php` - Carousel functionality
- `schema-markup.php` - SEO schema
- `newsletter-social.php` - Newsletter & social share
- `video-integration.php` - Video embeds
- `you-may-also-like.php` - You may also like section

### Hooks Available:
- `minimal_nails_display_best_products()` - Display function
- `minimal_nails_display_you_may_also_like()` - Display function
- `minimal_nails_post_has_affiliate_links()` - Check function

---

## 📝 Notes

1. **Affiliate Links**: Luôn sử dụng affiliate URLs từ Amazon Associates
2. **Disclosure**: Đảm bảo disclosure được hiển thị theo yêu cầu của Amazon
3. **Testing**: Test tất cả links trước khi publish
4. **SEO**: Schema markup giúp SEO, nhưng cần valid product data
5. **Performance**: Product carousel sử dụng Slick.js từ CDN

---

## 🚀 Quick Start Checklist

- [ ] Cấu hình Affiliate Disclosure trong Customizer
- [ ] Thêm Amazon product vào một post test
- [ ] Test shortcode `[amazon_product]`
- [ ] Thêm Best Products vào một post
- [ ] Add Related Products widget vào sidebar
- [ ] Test Product Carousel shortcode
- [ ] Test Social Share buttons
- [ ] Test Newsletter signup form
- [ ] Verify Schema markup trong source code
- [ ] Test click tracking

---

**Chúc bạn thành công với Amazon Affiliate marketing! 🎉**

