import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { NestExpressApplication } from '@nestjs/platform-express';
import { CorsOptions } from '@nestjs/common/interfaces/external/cors-options.interface';

async function bootstrap() {
	const app = await NestFactory.create<NestExpressApplication>(AppModule);

	// Enable CORS
	const corsOptions: CorsOptions = {
		origin: process.env.WEB_URL,
		methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
		credentials: true,
	}
	app.enableCors(corsOptions);

	await app.listen(process.env.PORT || 3000);

	console.log(`Application is running on: ${await app.getUrl()}`)
}
bootstrap();
