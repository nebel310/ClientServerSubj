import { useState } from 'react';
import defaultAvatar from '../avatar.jpg'; // изображение из папки проекта

function ProfileCard() {
  // состояние для хранения URL выбранного изображения
  const [avatar, setAvatar] = useState(defaultAvatar);

  // обработчик выбора файла
  const handleAvatarChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      const imageUrl = URL.createObjectURL(file);
      setAvatar(imageUrl);
    }
  };

  return (
    <div style={{ border: '1px solid #ccc', padding: '20px', maxWidth: '300px' }}>
      <h1>Моя визитка</h1>

      {/* возможность загружать изображение для аватарки */}
      <div>
        <img
          src={avatar}
          alt="Аватар"
          style={{ width: '100px', height: '100px', objectFit: 'cover' }}
        />
        <br />
        <input type="file" accept="image/*" onChange={handleAvatarChange} />
      </div>

      <p><strong>Имя:</strong> Влад</p>
      <p><strong>Специальность:</strong> Программист</p>
      <p><strong>Группа:</strong> БИВТ-24-1</p>
      <p>Джуниор пайтон разработчик. Пишу бекенд на FastAPI и не только</p>
    </div>
  );
}

export default ProfileCard;