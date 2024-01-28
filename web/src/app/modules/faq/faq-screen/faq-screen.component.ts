import { Component, OnInit } from '@angular/core';
import { Faq } from 'src/app/common/models/faq.model';
import { FaqService } from 'src/app/common/services/faq/faq.service';

@Component({
  selector: 'app-faq-screen',
  templateUrl: './faq-screen.component.html',
  styleUrl: './faq-screen.component.scss'
})
export class FaqScreenComponent implements OnInit {
  public faqs: Faq[] = []
  public isLoading: boolean = true

  constructor(private readonly faqService: FaqService) { }

  ngOnInit() {
    this.faqService.getFaqs().subscribe(faqs => {
      this.faqs = faqs
      this.isLoading = false;
    })
  }
}
