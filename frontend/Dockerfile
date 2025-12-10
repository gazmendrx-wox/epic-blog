FROM node:24-alpine

# Set working directory
WORKDIR /app

# Copy application files from app subdirectory
COPY app/ ./

# Install dependencies
RUN npm install

# Build the application
RUN npm run build

# Expose port 3000
EXPOSE 3000

# Start the application
CMD ["node", ".output/server/index.mjs"]
