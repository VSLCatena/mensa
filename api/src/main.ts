import { NestFactory } from '@nestjs/core';
import { SwaggerModule, DocumentBuilder } from '@nestjs/swagger';
import { AppModule } from './app.module';
import { NestExpressApplication } from '@nestjs/platform-express';
import { CorsOptions } from '@nestjs/common/interfaces/external/cors-options.interface';

async function bootstrap() {
	const app = await NestFactory.create<NestExpressApplication>(AppModule);

	// Enable CORS
	const corsOptions: CorsOptions = {
		origin: process.env.WEB_URL,
		methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
		credentials: true
	};
	app.enableCors(corsOptions);

	// Setup swagger documentation for development environment
	if (Boolean(process.env.DEV_ENVIRONMENT).valueOf()) {
		const swaggerOptions = new DocumentBuilder()
			.setTitle('Mensa API')
			.setDescription('This is the mensa API of V.S.L. Catena')
			.setVersion('1.0')
			.build();

		const swaggerDocument = SwaggerModule.createDocument(
			app,
			swaggerOptions
		);
		SwaggerModule.setup('swagger', app, swaggerDocument);
	}
	await app.listen(process.env.PORT || 3000);

	console.log(`Application is running on: ${await app.getUrl()}`);
}
bootstrap();
