# 🎬 Animation & Effects Guide (Hướng Dẫn Sử Dụng Animations)

## Quick Start - Bắt Đầu Nhanh

### Cách thêm animation vào HTML element:

```html
<!-- Fade In -->
<div class="animate-fade-in">This will fade in</div>

<!-- Fade In Up (từ dưới lên) -->
<div class="animate-fade-in-up">Slides up while fading in</div>

<!-- Fade In Down (từ trên xuống) -->
<div class="animate-fade-in-down">Slides down while fading in</div>

<!-- Scale In -->
<div class="animate-scale-in">Grows while appearing</div>

<!-- Bounce -->
<div class="animate-bounce">Bounces continuously</div>

<!-- Float -->
<div class="animate-floating">Floats up and down</div>

<!-- Pulse -->
<div class="animate-pulse">Pulses continuously</div>
```

---

## 📂 Animation Categories

### 1. **Fade Animations** (Hiệu ứng mờ)
```
.animate-fade-in          → Simple fade in
.animate-fade-in-up       → Fade + slide up
.animate-fade-in-down     → Fade + slide down
```

### 2. **Slide Animations** (Hiệu ứng trượt)
```
.animate-slide-in-left    → Slide from left
.animate-slide-in-right   → Slide from right
```

### 3. **Scale & Zoom** (Hiệu ứng co giãn)
```
.animate-scale-in         → Scale from 0.95 to 1
.animate-zoom-in          → Zoom from small
.animate-zoom-out         → Zoom to small
```

### 4. **Bounce Effects** (Hiệu ứng nảy)
```
.animate-bounce           → Simple bounce
.animate-bounce-in        → Bounce on entrance
```

### 5. **Rotate Effects** (Hiệu ứng xoay)
```
.animate-rotate           → 360° rotation
.animate-flip-in-x        → 3D flip on X axis
.animate-flip-in-y        → 3D flip on Y axis
```

### 6. **Continuous Effects** (Hiệu ứng liên tục)
```
.animate-pulse            → Pulsing effect
.animate-floating         → Floating up/down
.animate-wave             → Wave motion
.animate-shake            → Shake effect
.animate-swing            → Swing side to side
.animate-wiggle           → Small wiggle
```

### 7. **Special Effects** (Hiệu ứng đặc biệt)
```
.animate-heartbeat        → Heartbeat effect
.animate-typewriter       → Typewriter text
.animate-blinking         → Blinking effect
```

---

## 🎨 Hover Effects (Hiệu ứng di chuột)

```html
<!-- Lift on hover -->
<div class="hover-lift">
    Card lifts when you hover
</div>

<!-- Grow on hover -->
<div class="hover-grow">
    Element grows on hover
</div>

<!-- Scale on hover -->
<div class="hover-scale">
    Element scales 1.1x on hover
</div>

<!-- Shadow on hover -->
<div class="hover-shadow">
    Shadow appears on hover
</div>

<!-- Color change on hover -->
<div class="hover-color">
    Text color changes on hover
</div>
```

---

## 💻 Examples - Các Ví Dụ

### Example 1: Dashboard Card
```html
<div class="card animate-fade-in-up hover-lift">
    <div class="card-header">
        <h5><i class="bi bi-speedometer2"></i> Dashboard</h5>
    </div>
    <div class="card-body">
        <p>Content here</p>
    </div>
</div>
```

### Example 2: Animated Button
```html
<button class="btn btn-primary animate-bounce-in hover-lift">
    <i class="bi bi-check"></i> Submit
</button>
```

### Example 3: Floating Box
```html
<div class="alert alert-success animate-fade-in-down">
    <i class="bi bi-check-circle"></i> Success!
</div>
```

### Example 4: Loading Spinner
```html
<div class="spinner-border animate-rotate">
    <span class="visually-hidden">Loading...</span>
</div>
```

### Example 5: Hover Card
```html
<div class="card hover-lift hover-shadow">
    <img src="image.jpg" alt="...">
    <div class="card-body">
        <h5 class="card-title">Card Title</h5>
    </div>
</div>
```

---

## 🎯 Common Patterns

### Pattern 1: Page Load Animation
```html
<!-- Fade in headers -->
<h1 class="animate-fade-in-down">Welcome</h1>

<!-- Stagger cards -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card animate-fade-in-up" style="animation-delay: 0s;">
            Card 1
        </div>
    </div>
    <div class="col-md-4">
        <div class="card animate-fade-in-up" style="animation-delay: 0.1s;">
            Card 2
        </div>
    </div>
    <div class="col-md-4">
        <div class="card animate-fade-in-up" style="animation-delay: 0.2s;">
            Card 3
        </div>
    </div>
</div>
```

### Pattern 2: Table Row Hover
```html
<table class="table table-hover">
    <tbody>
        <tr class="interactive-element">
            <td>Row 1</td>
        </tr>
        <tr class="interactive-element">
            <td>Row 2</td>
        </tr>
    </tbody>
</table>
```

### Pattern 3: Modal Animation
```html
<div class="modal" id="myModal">
    <div class="modal-dialog animate-scale-in">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Modal Title</h5>
            </div>
            <div class="modal-body">
                Content...
            </div>
        </div>
    </div>
</div>
```

---

## ⏱️ Animation Timing

Mặc định animations:
- Fade: 0.5s - 0.6s
- Slide: 0.5s
- Bounce: 0.6s
- Pulse: 2s (infinite)
- Floating: 3s (infinite)

### Custom Timing (CSS):
```css
/* Slower animation */
.my-element {
    animation-duration: 1.5s !important;
}

/* Faster animation */
.my-element {
    animation-duration: 0.3s !important;
}

/* Delay */
.my-element {
    animation-delay: 0.5s;
}

/* Repeat count */
.my-element {
    animation-iteration-count: 3;
}
```

---

## 🎨 Combining Animations

```html
<!-- Multiple animations -->
<div class="card animate-fade-in hover-lift">
    Content that fades in and lifts on hover
</div>

<!-- Staggered animations -->
<div class="card animate-fade-in-up" style="animation-delay: 0s;">Card 1</div>
<div class="card animate-fade-in-up" style="animation-delay: 0.1s;">Card 2</div>
<div class="card animate-fade-in-up" style="animation-delay: 0.2s;">Card 3</div>
```

---

## 🎓 Advanced: Custom Animations

### Tạo animation riêng:
```css
/* Add to your CSS file */
@keyframes myCustomAnimation {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.my-custom-animation {
    animation: myCustomAnimation 0.6s ease-out;
}
```

### Sử dụng:
```html
<div class="my-custom-animation">
    Animated with custom animation
</div>
```

---

## ♿ Accessibility

### Respects User Preferences:
```css
/* Automatically reduced for users with motion preferences */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
```

### Best Practices:
1. ✅ Không overuse animations
2. ✅ Đảm bảo content visible ngay (không chỉ animation)
3. ✅ Test trên slow devices
4. ✅ Respect user preferences

---

## 🚀 Performance Tips

1. **Use transform & opacity** (fastest)
   ```css
   transform: translateY(-10px); /* Good */
   opacity: 0.5; /* Good */
   ```

2. **Avoid animating:** width, height, left, top, margin, padding
   ```css
   width: 200px; /* Bad for animation */
   ```

3. **Use will-change** (use sparingly):
   ```css
   .animated-element {
       will-change: transform;
   }
   ```

4. **GPU acceleration:**
   ```css
   transform: translateZ(0); /* Enables GPU */
   backface-visibility: hidden;
   perspective: 1000px;
   ```

---

## 📊 Animation Classes Reference

### Entrance Animations
| Class | Effect | Duration |
|-------|--------|----------|
| `animate-fade-in` | Fade in | 0.5s |
| `animate-fade-in-up` | Fade up | 0.6s |
| `animate-fade-in-down` | Fade down | 0.6s |
| `animate-scale-in` | Scale up | 0.4s |
| `animate-slide-in-left` | Slide from left | 0.5s |
| `animate-slide-in-right` | Slide from right | 0.5s |
| `animate-zoom-in` | Zoom in | 0.4s |
| `animate-bounce-in` | Bounce in | 0.6s |
| `animate-flip-in-x` | Flip X | 0.6s |
| `animate-flip-in-y` | Flip Y | 0.6s |

### Continuous Animations
| Class | Effect | Duration |
|-------|--------|----------|
| `animate-pulse` | Pulse | 2s ∞ |
| `animate-bounce` | Bounce | 1s ∞ |
| `animate-floating` | Float | 3s ∞ |
| `animate-rotate` | Rotate | 2s ∞ |
| `animate-wave` | Wave | 1s ∞ |
| `animate-heartbeat` | Heartbeat | 1.2s ∞ |
| `animate-shake` | Shake | 0.5s |
| `animate-swing` | Swing | 1s |
| `animate-wiggle` | Wiggle | 0.5s |

### Hover Effects
| Class | Effect |
|-------|--------|
| `hover-lift` | Lifts + shadow |
| `hover-grow` | Scales 1.05 |
| `hover-scale` | Scales 1.1 |
| `hover-shadow` | Shadow appears |
| `hover-color` | Color changes |

---

## 🎯 Cheat Sheet

```html
<!-- Quick Copy-Paste -->

<!-- Fade in -->
<div class="animate-fade-in">Text</div>

<!-- Button with animation -->
<button class="btn btn-primary animate-bounce-in">Click me</button>

<!-- Card with hover -->
<div class="card hover-lift">Content</div>

<!-- Loading spinner -->
<div class="spinner-border animate-rotate"></div>

<!-- Success message -->
<div class="alert alert-success animate-fade-in-down">Success!</div>

<!-- Floating element -->
<div class="badge bg-primary animate-floating">New</div>

<!-- Staggered list -->
<ul>
    <li class="animate-fade-in-up" style="animation-delay: 0s;">Item 1</li>
    <li class="animate-fade-in-up" style="animation-delay: 0.1s;">Item 2</li>
    <li class="animate-fade-in-up" style="animation-delay: 0.2s;">Item 3</li>
</ul>
```

---

## 📚 Resources

- CSS Animations: https://developer.mozilla.org/en-US/docs/Web/CSS/animation
- Easing Functions: https://cubic-bezier.com/
- Animate.css: https://animate.style/

---

**Last Updated**: 2025-12-08
**Version**: 1.0
**Status**: ✅ Ready to use
