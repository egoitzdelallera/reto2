<template>
    <div class="user-card">
      <div class="user-card-left">
          <div class="profile-icon">
               <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="12" cy="12" r="11.5" stroke="#CDCDCD"/>
                  <path d="M4.8 19.2L19.2 4.8M4.8 4.8L19.2 19.2" stroke="#CDCDCD"/>
              </svg>
          </div>
          <div class="user-details">
          <div class="user-name">{{ name }}</div>
          <div class="user-role">{{ role }}</div>
        </div>
      </div>
  
      <div class="user-card-mid">
        <div class="user-location">{{ location }}</div>
        <div class="user-address">{{ address }}</div>
      </div>
      <div class="user-card-right">
        <div class="user-email-label">Correo Electrónico</div>
        <div class="user-email">{{ email }}</div>
      </div>
  
      <div class="user-card-status">
          <button class="status-button" :class="{ 'status-enabled': isEnabled }">
            <svg v-if="isEnabled" width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.66666 1.33334L3.66666 6.33334L1.33333 4" stroke="black" stroke-width="2" stroke-linecap="round"/>
            </svg>
              {{ isEnabled ? 'Habilitado' : 'Deshabilitado' }}
          </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      name: {
        type: String,
        required: true
      },
      role: {
        type: String,
        required: true
      },
        location: {
        type: String,
        required: true
      },
      address: {
        type: String,
        required: true
      },
      email: {
          type: String,
          required: true
      },
      isEnabled:{
          type: Boolean,
          default: true
      }
  
    }
  };
  </script>
  
  <style scoped>
  .user-card {
    display: flex;
    align-items: center;
    padding: 16px;
    border-radius: 4px;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    font-family: 'Inter', sans-serif;
  }
  
  
  .user-card-left {
    display: flex;
    align-items: center;
      margin-right: 20px;
  }
  
  .profile-icon{
      margin-right: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
  
  }
  
  
  .user-details {
    display: flex;
    flex-direction: column;
  }
  
  .user-name {
    font-size: 16px;
    font-weight: 500;
    color: #000000;
  }
  
  .user-role {
    font-size: 14px;
    color: #707070;
  }
  
  .user-card-mid {
      margin-right: 20px;
      display: flex;
      flex-direction: column;
      font-size: 14px;
      color: #000000;
  }
  
  
  .user-location {
    font-size: 14px;
    color: #000000;
    white-space: nowrap;
  }
  
  .user-address {
    font-size: 14px;
      color: #707070;
    white-space: nowrap;
  }
  
  .user-card-right {
      display: flex;
      flex-direction: column;
      margin-right: 20px;
  }
  .user-email-label{
      font-size: 12px;
      color: #707070;
  }
  .user-email {
      font-size: 14px;
      color: #000000;
  }
  .user-card-status{
      margin-left: auto;
  }
  .status-button {
    display: flex;
    align-items: center;
      justify-content: center;
      gap: 4px;
    padding: 6px 12px;
    border: 1px solid #E0E0E0;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
      color: #000000;
    background-color: transparent;
  
  }
  .status-button.status-enabled{
    border-color: #D3D3D3;
  }
  </style>