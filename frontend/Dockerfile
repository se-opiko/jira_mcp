FROM node:20

# Set working directory
WORKDIR /var/www/frontend

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy application files
COPY . .

# Expose port 3000
EXPOSE 3000

# Start Nuxt development server
CMD ["npm", "run", "dev"]
